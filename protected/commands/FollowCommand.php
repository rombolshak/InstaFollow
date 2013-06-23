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
            if (sizeof($tokens) == 0) {
                //$this->setWaitStatus($user, 'noKeys');
                continue;
            }

            $peopleToFollow = $this->getPeople($user, $tokens);

            if (sizeof($peopleToFollow) == 0) {
                $this->setWaitStatus($user);
                continue;
            }

            $user->pos += sizeof($peopleToFollow);
            $user->save();

            for ($i = 0; $i < sizeof($peopleToFollow); ++$i) {
                try {
                    $instagram->setAccessToken($tokens[(int)floor($i / 2)]->token);
                    $instagram->setProxy($tokens[(int)floor($i / 2)]->key->proxy, Yii::app()->params['proxyAuthString']);
                    $status = $instagram->modifyUserRelationship($peopleToFollow[$i]->fid, 'follow');
                    if ($status['meta']['code'] != 200) {
                        Yii::log('FollowCommand: '.$status['meta']['error_message'].'. Token = '.($tokens[(int)floor($i / 2)]->token), CLogger::LEVEL_WARNING);
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
     * @param $user
     * @param $tokens
     * @return array
     */
    private function getPeople($user, $tokens)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('lid', $user->lid);
        $criteria->limit = min(7500 - $user->user->follows, sizeof($tokens) * 2);
        $criteria->offset = $user->pos;
        $peopleToFollow = PeopleToFollow::model()->findAll($criteria);
        return $peopleToFollow;
    }

    /**
     * @param FollowManager $user
     */
    private function setWaitStatus($user, $status='wait')
    {
        $user->status = $status;
        $user->time = new CDbExpression('ADDDATE(NOW(), INTERVAL '.$user->timeInterval.' MINUTE)');
        $user->save();
    }
}