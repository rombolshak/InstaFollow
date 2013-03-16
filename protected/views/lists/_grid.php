<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id'=>'lists-grid',
    'dataProvider'=>$model->search(),
    //'filter'=>$model,
    'columns'=>array(
        //'lid',
        'name',
        'count',
        'collected',
        array(
            'header'=>"%",
            'type'=>'raw',
            'value'=>'$data->collected / $data->count'
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{delete}'
        ),
    ),
)); ?>