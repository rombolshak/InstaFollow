<?php
/* @var $this KeysController */
/* @var $model Keys */

$this->breadcrumbs=array(
	'Keys'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Keys', 'url'=>array('create')),
);

?>

<h1>Ключи</h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'keys-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'name' => 'kid',
            'htmlOptions'=>array('width'=>20),
        ),
		array(
            'name'=>'clientId',
            'htmlOptions'=>array('width'=>250),
        ),
		//'clientSecret',
        array(
            'name'=>'proxy',
            'htmlOptions'=>array('width'=>150),
        ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
