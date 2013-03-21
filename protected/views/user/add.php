<?php
/* @var $this UsersController */
/* @var $keys Keys */

$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
	'Добавить',
);

$this->menu=array(
    array('label'=>'Операции', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'Пользователи', 'url'=>array('admin')),
	array('label'=>'Добавить', 'url'=>array('add'), 'itemOptions' => array('class'=>'active')),
    array('label'=>'Информация по ключам', 'url'=>array('keys')),
);
?>

<h1>Добавить аккаунт</h1>

<?php
$this->widget('bootstrap.widgets.TbButton',array(
    'label' => 'Войти через Instagram',
    'type' => 'primary',
    'size' => 'large',
    'htmlOptions'=>array(
        'submit'=>$link,
    ),
));
//$this->widget('bootstrap.widgets.TbButtonGroup', array(
//    'buttons' => array(
//        array( 'label'=>'Ключ',
//        'items' => $keys),
//    ))
//);
?>
