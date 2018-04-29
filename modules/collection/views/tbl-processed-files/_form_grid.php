<?php 
use kartik\grid\GridView;
?>
<div class="grid-search large-search">
    <?= $this->render('_search', ['model' => $searchModel]); ?>
</div>
<?php

$attribute = [
    
    ['attribute' => 'cp_code', 'filter' => true],
    ['attribute' => 'file_path', 'filter' => false],
    ['attribute' => 'vendor_id', 'filter' => true],
    //['attribute' => 'processed_at', 'filter' => false],
//    ['attribute' => 'processed_at','value'=>function($model){
//        return date('d-m-Y',strtotime($model->processed_at));
//    }, 'filter' => false],
    ['attribute' => 'processed_at',
    'filterType'=>GridView::FILTER_DATE,
    'filterWidgetOptions'=>[
        'pluginOptions'=>['format'=>'dd-mm-yyyy',
            'autoclose'=>true]
    ],
    'value' => function($model) {
        return Yii::$app->controls->view_date($model->processed_at);
    }],
    ['attribute' => 'dcsCode.union_code', 'value' => function($model) {
            return Yii::$app->general->getUnionName($model);
    }, 'visible' => true, 'filter' => false],
    ['attribute' => 'dcs_code','value'=>'dcsCode.dcs_name', 'filter' => false],
];

$grid_option = [
    'id' => 'processed-files-list',
    'attributes' => $attribute,
    'active_column' => false,
    //'actions' => []
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
