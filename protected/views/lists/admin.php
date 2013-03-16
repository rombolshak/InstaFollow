<?php
/* @var $this ListsController */
/* @var $model Lists */

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile(
    Yii::app()->assetManager->publish(
        Yii::app()->basePath . '/js/lists'). '/grid.js',
    CClientScript::POS_END);

$this->breadcrumbs=array(
	'Списки'=>array('index'),
	'Главная',
);

$this->menu=array(
	array('label'=>'Операции', 'itemOptions'=>array('class'=>'nav-header')),
    array('label'=>'Списки', 'url'=>array('index'), 'itemOptions'=>array('class'=>'active')),
    array('label'=>'Создать', 'url'=>array('create')),
);
?>

<h1>Списки</h1>

<?php $this->renderPartial("_grid", array('model'=>$model)); ?>
