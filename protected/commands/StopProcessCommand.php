<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roma
 * Date: 17.05.13
 * Time: 22:57
 * To change this template use File | Settings | File Templates.
 */

class StopProcessCommand extends CConsoleCommand {
    public function run($args) {
        $time = Yii::app()->db->createCommand('select str_to_date(
                        concat(
                            date(adddate(now(), interval 1 day))
                            , " 09:00")
                        , "%Y-%m-%d %h:%i") as t')->queryAll();
        //CVarDumper::dump($time);
        FollowManager::model()->updateAll(
            array(
                'status'=>'wait',
                'time' => $time[0]['t'],
            ),
            'status != "done" AND status != "notStarted"');
    }
}