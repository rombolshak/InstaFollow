<?php
/* @var $this UserController */

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

<script>
    function redirect() {
        window.location.href = "<?php echo $this->createUrl('add'); ?>"
    }
    $(document).ready(function(){
        setTimeout(redirect, 3000);
    });
</script>

<?php
$this->widget('bootstrap.widgets.TbAlert', array(
    'block'=>true, // display a larger alert block?
    'fade'=>false, // use transitions?
    //'closeText'=>'×', // close link text - if set to false, no close link is displayed
    //'alerts'=>array( // configurations per alert type
    //    'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
    //),
));
?>
