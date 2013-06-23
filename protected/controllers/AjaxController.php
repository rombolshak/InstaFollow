<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roma
 * Date: 14.03.13
 * Time: 21:47
 * To change this template use File | Settings | File Templates.
 */

class AjaxController extends Controller
{
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
            array('deny',  // deny all user
                'users'=>array('*'),
            ),
        );
    }

    public function actionFindUserByName($name)
    {
        $instagram = Yii::app()->instagram->getInstagramApp();
        $token = UserTokens::model()->find(array(
            'select'=>'token, rand() as rand',
            'order'=>'rand'
        ));
        $instagram->setAccessToken($token->token);
        $searchedUser = $instagram->searchUser($name);
        if (!isset($searchedUser['data'][0]))
            echo json_encode(array('error' => true));
        else echo json_encode($searchedUser['data'][0]);
    }

    public function actionUserStart($uid, $lid = 0, $interval = 360) {
        if ($lid == 0) {echo 'Lid is null'; return;}
        if (Lists::model()->findByPk($lid) == null) {echo 'No such list '.$lid; return;}
        if (Users::model()->findByPk($uid) == null) {echo 'No such user '.$uid; return;}

        /** @var FollowManager $manager  */
        $manager = FollowManager::model()->findByPk($uid);
        //CVarDumper::dump($manager, 10, true);
        $manager->lid = $lid;
        $manager->timeInterval = (int)$interval;
        $manager->pos = 0;
        $manager->status = 'follow';
        if (!$manager->save())
            throw new CHttpException(500, $manager->errors);
    }

    public function actionUserPause($uid) {
        if (Users::model()->findByPk($uid) == null) {echo 'No such user '.$uid; return;}

        /** @var FollowManager $manager  */
        $manager = FollowManager::model()->findByPk($uid);
        $manager->paused = 1;
        $manager->save();
    }

    public function actionUserContinue($uid) {
        if (Users::model()->findByPk($uid) == null) {echo 'No such user '.$uid; return;}

        /** @var FollowManager $manager  */
        $manager = FollowManager::model()->findByPk($uid);
        $manager->paused = 0;
        if (!$manager->save())
            CVarDumper::dump($manager->errors);
    }

    public function actionUserStop($uid) {
        if (Users::model()->findByPk($uid) == null) {echo 'No such user '.$uid; return;}

        /** @var FollowManager $manager  */
        $manager = FollowManager::model()->findByPk($uid);
        $manager->status = 'done';
        $manager->save();
    }
}