<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    public $defaultAction = 'admin';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'users'=>array('@'),
			),
            array('allow',
                'actions'=>array('emptyKeys', 'add'),
                'users'=>array('*')
            ),
			array('deny',  // deny all user
				'users'=>array('*'),
			),
		);
	}

    public function actionEmptyKeys() {
        $res = array();
        $users = Yii::app()->db->createCommand("SELECT `name` FROM `".Users::model()->tableName().
            "` WHERE `uid` NOT IN (SELECT `uid` FROM `".UserTokens::model()->tableName()."`)
            OR
            `uid` IN (SELECT `uid` FROM `".UserTokens::model()->tableName()."` GROUP BY `uid` HAVING count(uid) < ".Keys::model()->count().")")->queryAll();
        foreach ($users as $user)
            $res[] = $user['name'];
        echo json_encode($res);
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd()
	{
        if (!isset($_GET['code'])) {
            $this->renderLink();
        }

        else { //авторизация успешна
            $kid = (int)$_GET['state'];
            $key = Keys::model()->findByPk($kid);
            if ($key == null)
                throw new CHttpException(403, "Неверный ключ приложения");

            list($accessToken, $instagramUser) = $this->getInstagramUser($key);

            $user = $this->createUser($instagramUser);
            $token = $this->createToken($kid, $user, $accessToken);

            if ((int)UserTokens::model()->countByAttributes(array('uid'=>$token->uid)) < (int)Keys::model()->count()) {
                $this->processNextKey($token);
            }

            else { // пользователь добавлен на все ключи
                Yii::app()->user->setFlash('success', '<strong>OK</strong> Пользователь успешно добавлен.');
                $this->render('addSuccess');
            }
        }
	}

    /**
     * @param $key
     * @return array
     * @throws CHttpException
     */
    private function getInstagramUser($key)
    {
        $instagram = new Instagram(array(
            'client_id' => $key->clientId,
            'client_secret' => $key->clientSecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => Yii::app()->createAbsoluteUrl('user/add'),
        ));
        $accessToken = $instagram->getAccessToken();
        $instagram->setAccessToken($accessToken);
        $curInstagramUser = $instagram->getCurrentUser();
        $instagramUser = $instagram->getUser($curInstagramUser->id);
        $instagramUser = $instagramUser['data'];
        if ($instagramUser == null) throw new CHttpException(500, "Instagram error");
        return array($accessToken, $instagramUser);
    }

    /**
     * @param $token
     */
    private function processNextKey($token)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('kid', '>'.$token->kid);
        $key = Keys::model()->find($criteria);
        $instagram = new Instagram(array(
            'client_id' => $key->clientId,
            'client_secret' => $key->clientSecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => Yii::app()->createAbsoluteUrl('user/add'),
        ));
        $link = $instagram->getAuthorizationUrl() . "&state=" . $key->kid;
        header('Location: ' . $link);
    }

    /**
     * @param $kid
     * @param $user
     * @param $instagramUser
     * @param $accessToken
     * @return CActiveRecord|UserTokens
     */
    private function createToken($kid, $user, $accessToken)
    {
        $token = UserTokens::model()->findByAttributes(array('kid' => $kid, 'uid' => $user->uid));
        if ($token == null) {
            $token = new UserTokens();
            $token->uid = $user->uid;
            $token->kid = $kid;
            $token->token = $accessToken;
            if (!$token->save())
                throw new CHttpException(500, $token->errors);
            return $token;
        }
        return $token;
    }

    /**
     * @param $instagramUser
     * @return CActiveRecord|Users
     */
    private function createUser($instagramUser)
    {
        $user = Users::model()->findByPk($instagramUser['id']);
        if ($user == null) {
            $user = new Users();
            $user->uid = $instagramUser['id'];
            $user->name = $instagramUser['username'];
            $user->picture = $instagramUser['profile_picture'];
            $user->followers = $instagramUser['counts']['followed_by'];
            $user->follows = $instagramUser['counts']['follows'];
            $user->save();

            $manager = new FollowManager();
            $manager->uid = $user->uid;
            $manager->save();
            return $user;
        }
        return $user;
    }

    private function renderLink()
    {
        $key = Keys::model()->find();
        $instagram = new Instagram(array(
            'client_id' => $key->clientId,
            'client_secret' => $key->clientSecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => Yii::app()->createAbsoluteUrl('user/add'),
        ));
        $link = $instagram->getAuthorizationUrl() . "&state=" . $key->kid;

        $this->render('add', array(
            'link' => $link,
        ));
    }

    /**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
        UserTokens::model()->deleteAll('uid='.$id);
        FollowManager::model()->deleteByPk($id);

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->redirect('admin');
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Users the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Users $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
