<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roma
 * Date: 22.03.13
 * Time: 20:08
 * To change this template use File | Settings | File Templates.
 */

class FollowCommand extends CConsoleCommand {
    public function run($args) {
        $instagram = new Instagram(array('qwe'=>'qwe'));
        $users = FollowManager::model()->findAllByAttributes(array('status'=>'follow', 'paused'=>false));
        foreach($users as $user) {
            if ($user->user->follows >= 7499) {
                $this->setWaitStatus($user);
                continue;
            }

            $tokens = UserTokens::model()->findAllByAttributes(array('uid'=>$user->uid));

            $criteria = new CDbCriteria();
            $criteria->compare('lid', $user->lid);
            $criteria->limit = min(7500 - $user->user->follows, sizeof($tokens) * 2);
            $criteria->offset = $user->pos;
            $peopleToFollow = PeopleToFollow::model()->findAll($criteria);

            if (sizeof($peopleToFollow) == 0) {
                $this->setWaitStatus($user);
                continue;
            }

            $user->pos += sizeof($peopleToFollow);
            $user->save();

            for ($i = 0; $i < sizeof($peopleToFollow); ++$i) {
                $instagram->setAccessToken($tokens[(int)floor($i / 2)]->token);
                $status = $instagram->modifyUserRelationship($peopleToFollow[$i]->fid, 'follow');
                if ($status['meta']['code'] != 200) {
                    Yii::log('FollowCommand: '.$status['meta']['error_message'].CVarDumper::dump($status));
                }
            }
        }
    }

    /**
     * @param FollowManager $user
     */
    private function setWaitStatus($user)
    {
        $user->status = 'wait';
        $user->time = new CDbExpression('ADDDATE(NOW(), INTERVAL 1 DAY)');
        $user->save();
    }
}