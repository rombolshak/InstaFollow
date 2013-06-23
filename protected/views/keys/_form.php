<?php
/* @var $this KeysController */
/* @var $model Keys */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'keys-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'clientId'); ?>
		<?php echo $form->textField($model,'clientId',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'clientId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'clientSecret'); ?>
		<?php echo $form->textField($model,'clientSecret',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'clientSecret'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'proxy'); ?>
        <?php echo $form->textField($model,'proxy',array('size'=>20,'maxlength'=>20)); ?>
        <?php echo $form->error($model,'proxy'); ?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->