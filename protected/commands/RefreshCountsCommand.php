<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roma
 * Date: 21.03.13
 * Time: 23:34
 * To change this template use File | Settings | File Templates.
 */

class RefreshCountsCommand extends CConsoleCommand {
    public function run($args) {
        $users = Users::model()->findAll();
        $token = UserTokens::model()->find(array(
            'select'=>'*, rand() as rand',
            'order'=>'rand',
        ));
        $instagram = new Instagram(array('qwe'=>'qwe'));
        $instagram->setAccessToken($token->token);

        foreach($users as $user) {
            $u = $instagram->getUser($user->uid);

            if ($u['meta']['code'] != 200) {
                Yii::log('RefreshCommand: '.$u['meta']['error_message'].". Token ".($token->token). ". ", CLogger::LEVEL_WARNING);
                if ($u['meta']['error_message'] == 'The "access_token" provided is invalid.') {
                    $token->token->delete();
                    Yii::log("Token was deleted", CLogger::LEVEL_INFO);
                }
                CVarDumper::dump($u);
                $token = UserTokens::model()->find(array(
                    'select'=>'*, rand() as rand',
                    'order'=>'rand',
                ));
                continue;
            }

            $user->followers = $u['data']['counts']['followed_by'];
            $user->follows = $u['data']['counts']['follows'];
            $user->save();
        }
    }
}