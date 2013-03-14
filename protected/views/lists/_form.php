<?php
/* @var $this ListsController */
/* @var $model Lists */
/* @var $form CActiveForm */
?>

<?php
Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile(
    Yii::app()->assetManager->publish(
        Yii::app()->basePath . '/js/'). '/lists.js',
    CClientScript::POS_END);
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'lists-form',
	'enableAjaxValidation'=>false,
     'type'=>'horizontal',
)); ?>

    <fieldset>
        <?php echo $form->textFieldRow($model,'lid', array('hint'=>'Значение в этом поле должно появиться автоматически, если будет найден пользователь с именем, указанным в следующем поле')); ?>
        <?php echo $form->textFieldRow($model,'name',array('maxlength'=>50, 'hint'=>'Пользователь, с которого в базу будет собираться, кого фолловить')); ?>
        <?php echo $form->textFieldRow($model,'count',array('maxlength'=>8, 'hint'=>'Сколько фолловеров необходимо добавить с базу в этого пользователя')); ?>
    </fieldset>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'ОК', 'type'=>'primary')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->