<?php
use kartik\grid\GridView;
?>
<div class="grid-search">
    <?php 
    echo $this->render('_search', ['model' => $searchModel]); ?>
</div>
<?php

$attribute = [  
        ['attribute' => 'dcsCode.union_code', 'value' => function($model) {
            return Yii::$app->general->getUnionName($model);
        }, 'visible' => true, 'filter' => false],
        ['attribute' =>   'dcs_code','value'=>'dcsCode.dcs_name','filter' => false],
//        ['header'=>'Date','attribute' => 'Dtdate','value'=>function($model){
//                return !empty($model->Dtdate)?date('d-m-Y',strtotime($model->Dtdate)):'(not set)';
//            }, 'filter' => false],
        ['header'=>'Date','attribute' => 'Dtdate',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->Dtdate);
        }],
        //['attribute' =>  'Dtdate','filter' => false],
        ['attribute' => 'Shift', 'value'=>'shiftCode.shift','filter' => false],
        //['attribute' =>  'c1Date','filter' => false],
//        ['attribute' => 'c1Date','value'=>function($model){
//                return !empty($model->c1Date)?date('d-m-Y',strtotime($model->c1Date)):'(not set)';
//            }, 'filter' => false],
        ['attribute' => 'c1Date',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->c1Date);
        }],
//        ['attribute' =>   'CycleNo','filter' => false],
        ['attribute' =>   'c1testing','filter' => true],
        //['attribute' =>   'c2Date','filter' => false],
//        ['attribute' => 'c2Date','value'=>function($model){
//                return !empty($model->c2Date)?date('d-m-Y',strtotime($model->c2Date)):'(not set)';
//            }, 'filter' => false],
        ['attribute' => 'c2Date',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->c2Date);
        }],
        ['attribute' => 'c2cycle','filter' => true],
            // 'c2testing',
            // 'c3Date',
            // 'c3cycle',
            // 'c3testing',
            // 'c4Date',
            // 'c4cycle',
            // 'c4testing',
            // 'c5Date',
            // 'c5cycle',
            // 'c5testing',
            // 'Counter',
            // 'CreatedDate',
            // 'ModifyDate',
  
];

$grid_option = [
    'id' => 'cleaning-dpu-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => TRUE,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
