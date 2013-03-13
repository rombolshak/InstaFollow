<?php
/* @var $this KeysController */
/* @var $model Keys */

$this->breadcrumbs=array(
	'Keys'=>array('index'),
	$model->kid,
);

$this->menu=array(
	array('label'=>'List Keys', 'url'=>array('index')),
	array('label'=>'Create Keys', 'url'=>array('create')),
	array('label'=>'Update Keys', 'url'=>array('update', 'id'=>$model->kid)),
	array('label'=>'Delete Keys', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->kid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Keys', 'url'=>array('admin')),
);
?>

<h1>View Keys #<?php echo $model->kid; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'kid',
		'clientId',
		'clientSecret',
	),
)); ?>
