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
            array('deny',  // deny all users
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
}