<?php
/* @var $this KeysController */
/* @var $data Keys */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('kid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->kid), array('view', 'id'=>$data->kid)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('clientId')); ?>:</b>
	<?php echo CHtml::encode($data->clientId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('clientSecret')); ?>:</b>
	<?php echo CHtml::encode($data->clientSecret); ?>
	<br />


</div>