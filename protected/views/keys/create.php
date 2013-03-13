<?php
/* @var $this KeysController */
/* @var $model Keys */

$this->breadcrumbs=array(
	'Keys'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Keys', 'url'=>array('index')),
	array('label'=>'Manage Keys', 'url'=>array('admin')),
);
?>

<h1>Create Keys</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>