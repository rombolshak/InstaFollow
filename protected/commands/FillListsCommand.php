<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roma
 * Date: 16.03.13
 * Time: 15:35
 * To change this template use File | Settings | File Templates.
 */

class FillListsCommand extends CConsoleCommand {
    public function run($args)
    {
        $lists = Lists::model()->getNotFilled();
        $tokens = UserTokens::model()->findAll(array(
            'limit'=>sizeof($lists),
            'select'=>'token, rand() as rand',
            'order'=>'rand'
        ));

        $instagram = new Instagram(array('qwe'=>'asd'));

        for ($i = 0; $i < sizeof($lists); ++$i) {

            /** @var Lists $list */
            $list = $lists[$i];

            /** @var int $collected */
            $collected = $list->collected;

            /** @var string $token */
            $token = $tokens[$i]->token;

            $instagram->setAccessToken($token);

            $runs = 60; // сколько раз еще нужно индексировать текущий список

            while ($runs > 0) {
                if ($list->cursor == "-1")
                {
                    // если курсор == -1, то больше нечего брать, фолловеры закончились
                    $list->count = $collected;
                    $list->save();
                    break;
                }
                $data = $instagram->getUserFollowedBy($list->lid, $list->cursor);

                if ($data['meta']['code'] == 200) {
                    $list->cursor = isset($data['pagination']['next_cursor']) ? $data['pagination']['next_cursor'] : "-1";
                    foreach($data['data'] as $user) {
                        //$man = PeopleToFollow::model()->findByAttributes(array('lid'=>$list->lid, 'fid'=>(int)$user['id']));
                        //if ($man == null) {
                            $man = new PeopleToFollow();
                            $man->fid = (int) $user['id'];
                            $man->lid = $list->lid;
                            $man->pos = $collected++;
                            $man->save();
                        //}
                    }
                    $list->save();
                    $runs--;
                }
                else {
                    if ($data['meta']['error_message'] == 'The "access_token" provided is invalid.') {
                        Yii::log("The \"access_token\" provided is invalid.\n
                        Token deleted\n.
                        You should autorize user @".$tokens[$i]->user->name." again on key #".$tokens[$i]->kid, 'warning');
                        $tokens[$i]->delete();
                        break;
                    }
                    else {
                        Yii::log("Error message from Instagram: \n".$data['meta']['error_message']."\n".
                        "Occured on list ".$list->name." (".$list->lid.")\n".
                        "With token ".$token." (Key #".$tokens[$i]->kid.", user @".$tokens[$i]->user->name.")", 'error');
                        break;
                    }
                }
            }
        }
    }
}