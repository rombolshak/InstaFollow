<?php
/* @var $this KeysController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Keys',
);

$this->menu=array(
	array('label'=>'Create Keys', 'url'=>array('create')),
	array('label'=>'Manage Keys', 'url'=>array('admin')),
);
?>

<h1>Keys</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
