<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roma
 * Date: 23.03.13
 * Time: 16:15
 * To change this template use File | Settings | File Templates.
 */

class UnfollowCommand extends CConsoleCommand {
    public function run($args) {
        $instagram = new Instagram(array('qwe'=>'qwe'));

        FollowManager::model()->updateAll(array('status'=>'unfollow'), '`status` = "wait" AND `time` < NOW()');
        $users = FollowManager::model()->findAllByAttributes(array('status'=>'unfollow', 'paused'=>false));

        foreach($users as $user) {
            if ($user->user->follows < 25) {
                $this->setNextStatus($user);
                continue;
            }

            $tokens = UserTokens::model()->findAllByAttributes(array('uid'=>$user->uid));

            if (sizeof($tokens) == 0) {
                //$this->setNextStatus($user);
                continue;
            }

            $instagram->setAccessToken($tokens[0]->token);
            $people = $instagram->getUserFollows($user->uid);
            if ($people['meta']['code'] != 200) {
                Yii::log("UnfollowCommand: ".$people['meta']['error_message'], CLogger::LEVEL_ERROR);
                if ($people['meta']['error_message'] == 'The "access_token" provided is invalid.') {
                    $tokens[0]->delete();
                    Yii::log("Token was deleted", CLogger::LEVEL_INFO);
                }
                CVarDumper::dump($people);
                continue;
            }
            $people = $people['data'];

            if (sizeof($people) < 5) {
                //$this->setNextStatus($user);
                continue;
            }

            for ($i = 0; $i < 2 * sizeof($tokens); ++$i) {
                try {
                    $instagram->setAccessToken($tokens[(int)floor($i / 2)]->token);
                    $instagram->setProxy($tokens[(int)floor($i / 2)]->key->proxy, Yii::app()->params['proxyAuthString']);
                    $status = $instagram->modifyUserRelationship($people[$i]['id'], 'unfollow');
                    if ($status['meta']['code'] != 200) {
                        Yii::log('UnfollowCommand: '.$status['meta']['error_message'].". Token ".($tokens[(int)floor($i / 2)]->token). ". ", CLogger::LEVEL_WARNING);
                        if ($status['meta']['error_message'] == 'The "access_token" provided is invalid.') {
                            $tokens[(int)floor($i / 2)]->delete();
                            Yii::log("Token was deleted", CLogger::LEVEL_INFO);
                        }
                        CVarDumper::dump($status);
                    }
                }
                catch (Exception $ex) {continue;}
            }
        }
    }

    /**
     * @param FollowManager $user
     */
    private function setNextStatus($user)
    {
        $count = PeopleToFollow::model()->count("lid = ".$user->lid);
        if ($user->pos >= $count - 1) {
            $user->status = 'done';
        }
        else {
            $user->status = 'follow';
        }
        $user->save();
    }
}