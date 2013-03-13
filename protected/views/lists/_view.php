<?php
/* @var $this ListsController */
/* @var $data Lists */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('lid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->lid), array('view', 'id'=>$data->lid)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('smart')); ?>:</b>
	<?php echo CHtml::encode($data->smart); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />


</div>