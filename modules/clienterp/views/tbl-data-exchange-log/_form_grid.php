<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use yii\helpers\Html;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model, $key, $index) {
                $code = $model['data_exchange_log_code'];
                return ['class' => 'checkbox-collection', 'value' => $code];
            }],
    ['attribute' => 'process_name'],
    ['attribute' => 'process_code', 'value' => function($model){
        if(strtolower($model->process_name) == 'member provisional'){
            return Yii::$app->general->getforeignkey($model->memberProvisionalCode, 'member_name');
        } else {
            return Yii::$app->general->getforeignkey($model->memberProvisionalFamilyDetailCode, 'family_member_name');
        }
    }, 'filter' => false, 'label' => 'name'],
    ['attribute' => 'resp_param_1'],
    ['attribute' => 'resp_param_2'],
    ['attribute' => 'resp_param_3'],
    ['attribute' => 'resp_param_4'],
    ['attribute' => 'resp_param_5'],
    ['attribute' => 'resp_param_6'],
    // ['attribute' => 'update_key'],
    [
        'attribute' => 'picked_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->picked_datetime);
        }],
    [
        'attribute' => 'response_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->response_datetime);
        }],
    ['attribute' => 'resp_desc'],
    ['attribute' => 'resp_status'],
    ['attribute' => 'data_post_status', 'filter' => array('1'=>'PENDING', '2'=>'SUCCESS','3'=>'ERROR'),
        'value' => function($model) {
            return ($model->data_post_status == 1) ? 'PENDING' : ($model->data_post_status == 2 ? 'SUCCESS' : 'ERROR');
        }],
];

$grid_option = [
    'id' => 'data-exchange-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
