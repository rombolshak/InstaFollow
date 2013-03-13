<?php
/* @var $this KeysController */
/* @var $model Keys */

$this->breadcrumbs=array(
	'Keys'=>array('index'),
	$model->kid=>array('view','id'=>$model->kid),
	'Update',
);

$this->menu=array(
	array('label'=>'List Keys', 'url'=>array('index')),
	array('label'=>'Create Keys', 'url'=>array('create')),
	array('label'=>'View Keys', 'url'=>array('view', 'id'=>$model->kid)),
	array('label'=>'Manage Keys', 'url'=>array('admin')),
);
?>

<h1>Update Keys <?php echo $model->kid; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>