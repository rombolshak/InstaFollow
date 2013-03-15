<?php
/* @var $this ListsController */
/* @var $model Lists */

$this->breadcrumbs=array(
	'Lists'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Операции', 'itemOptions'=>array('class'=>'nav-header')),
    array('label'=>'Списки', 'url'=>array('index'), 'itemOptions'=>array('class'=>'active')),
    array('label'=>'Создать', 'url'=>array('create')),
);
?>

<h1>Списки</h1>


<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'lists-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'lid',
		'name',
		'count',
        'collected',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{delete}'
		),
	),
)); ?>
