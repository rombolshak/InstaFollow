<?php
/* @var $this ListsController */
/* @var $model Lists */

$this->breadcrumbs=array(
	'Lists'=>array('index'),
	$model->name=>array('view','id'=>$model->lid),
	'Update',
);

$this->menu=array(
	array('label'=>'List Lists', 'url'=>array('index')),
	array('label'=>'Create Lists', 'url'=>array('create')),
	array('label'=>'View Lists', 'url'=>array('view', 'id'=>$model->lid)),
	array('label'=>'Manage Lists', 'url'=>array('admin')),
);
?>

<h1>Update Lists <?php echo $model->lid; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>