<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
);

$this->menu=array(
    array('label'=>'Операции', 'itemOptions'=>array('class'=>'nav-header')),
    array('label'=>'Пользователи', 'url'=>array('admin'), 'itemOptions' => array('class'=>'active')),
    array('label'=>'Добавить', 'url'=>array('add')),
    array('label'=>'Информация по ключам', 'url'=>array('keys')),
);

Yii::app()->clientScript->registerScript('grid-update', '$(document).ready(function(){
    setInterval(function(){
        $("#users-grid").yiiGridView("update");
    }, 60000);

 });', CClientScript::POS_END);

?>
<h1>Пользователи</h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'users-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
        array(
            'header'=>'',
            'type'=>'raw',
            'value'=>'CHtml::image($data->picture, "", array("width"=>100))',
        ),
		//'uid',
		'name',
        array(
            'header'=>'Ключей',
            'type'=>'raw',
            'value'=>'$data->tokensCount'
        ),
        array(
            'header'=>'Список',
            'type'=>'raw',
            'value'=>'CHtml::dropDownList("list", $data->manager->lid, Lists::getListsWithCount(), array("prompt"=>"Выберите", "disabled"=> ($data->manager->status != "notStarted") && ($data->manager->status != "done")))',
        ),
        array(
            'header'=>'Ожидание (мин)',
            'type'=>'raw',
            'value'=>'CHtml::textField("timeInterval", $data->manager->timeInterval, array("class"=>"input-medium", "disabled"=> ($data->manager->status != "notStarted") && ($data->manager->status != "done")))'
        ),
		'followers',
        'follows',
        array(
            'header'=>'Статус',
            'type'=>'raw',
            'value'=>'$data->getStatus()',
        ),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{start}{pause}{continue}{stop}{delete}',
            'buttons' => array(
                'start'=>array(
                    'label'=>"Запустить",
                    'icon'=>'play',
                    'visible'=>'$data->manager->status == \'notStarted\' || $data->manager->status == \'done\'',
                    'url'=>'Yii::app()->createAbsoluteUrl("ajax/userStart", array("uid"=>$data->uid));',
                    'click' => " function(){
                        var urlWithLidAndTime = $(this).attr('href') + '&lid=' + $($(this).parent().parent().children()[3]).children().val() + '&interval=' + $($(this).parent().parent().children()[4]).children().val();
                        console.log(urlWithLidAndTime);
                        $.fn.yiiGridView.update('users-grid', {
                                        type:'POST',
                                        url: urlWithLidAndTime,
                                        success:function(data) {
                                              $.fn.yiiGridView.update('users-grid');
                                        }
                                    });
                                    return false;
                    }
                    ",
                ),
                'pause'=>array(
                    'label'=>"Приостановить",
                    'icon'=>'pause',
                    'visible'=>'($data->manager->paused == 0) && ($data->manager->status != \'notStarted\') && ($data->manager->status != \'done\')',
                    'url'=>'Yii::app()->createAbsoluteUrl("ajax/userPause", array("uid"=>$data->uid));',
                    'click' => " function(){
                        $.fn.yiiGridView.update('users-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                              $.fn.yiiGridView.update('users-grid');
                                        }
                                    });
                                    return false;
                    }
                    ",
                ),
                'continue'=>array(
                    'label'=>"Продолжить",
                    'icon'=>'play',
                    'visible'=>'$data->manager->paused == 1',
                    'url'=>'Yii::app()->createAbsoluteUrl("ajax/userContinue", array("uid"=>$data->uid));',
                    'click' => " function(){
                        $.fn.yiiGridView.update('users-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                              $.fn.yiiGridView.update('users-grid');
                                        }
                                    });
                                    return false;
                    }
                    ",
                ),
                'stop'=>array(
                    'label'=>"Остановить",
                    'icon'=>'stop',
                    'visible'=>'($data->manager->status != \'notStarted\') && ($data->manager->status != \'done\')',
                    'url'=>'Yii::app()->createAbsoluteUrl("ajax/userStop", array("uid"=>$data->uid));',
                    'click' => " function(){
                        $.fn.yiiGridView.update('users-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                              $.fn.yiiGridView.update('users-grid');
                                        }
                                    });
                                    return false;
                    }
                    ",
                ),
            ),
		),
	),
)); ?>
