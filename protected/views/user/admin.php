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
            'value'=>'CHtml::image($data->picture, "", array("width"=>50))',
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
            'value'=>'$data->manager->list->name',
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
            'template'=>'{delete}',
		),
	),
)); ?>
