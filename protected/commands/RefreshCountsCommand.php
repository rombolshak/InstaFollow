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
        $token = UserTokens::model()->find();
        $instagram = new Instagram(array('qwe'=>'qwe'));
        $instagram->setAccessToken($token->token);

        foreach($users as $user) {
            $u = $instagram->getUser($user->uid);
            $user->followers = $u['data']['counts']['followed_by'];
            $user->follows = $u['data']['counts']['follows'];
            $user->save();
        }
    }
}