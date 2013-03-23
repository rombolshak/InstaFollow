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
            if ($user->user->follows < 5) {
                $this->setNextStatus($user);
                continue;
            }

            $tokens = UserTokens::model()->findAllByAttributes(array('uid'=>$user->uid));

            $instagram->setAccessToken($tokens[0]->token);
            $people = $instagram->getUserFollows($user->uid);
            if ($people['meta']['code'] != 200) {
                Yii::log("UnfollowCommand: ".$people['meta']['error_message'], CLogger::LEVEL_ERROR);
                CVarDumper::dump($people);
                continue;
            }
            $people = $people['data'];

            if (sizeof($people) < 5) {
                $this->setNextStatus($user);
                continue;
            }

            for ($i = 0; $i < 2 * sizeof($tokens); ++$i) {
                $instagram->setAccessToken($tokens[(int)floor($i / 2)]->token);
                $status = $instagram->modifyUserRelationship($people[$i]['id'], 'unfollow');
                if ($status['meta']['code'] != 200) {
                    Yii::log('UnfollowCommand: '.$status['meta']['error_message'].". Token ".($tokens[(int)floor($i / 2)]->token). ". ", CLogger::LEVEL_WARNING);
                    CVarDumper::dump($status);
                }
            }
        }
    }

    /**
     * @param FollowManager $user
     */
    private function setNextStatus($user)
    {
        $count = Lists::model()->count("lid = ".$user->lid);
        if ($user->pos >= $count - 1) {
            $user->status = 'done';
        }
        else {
            $user->status = 'follow';
        }
        $user->save();
    }
}