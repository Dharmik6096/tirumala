<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'visible' => TRUE],
    ['attribute' => 'customer_type', 'value' => function($model) {
            return isset($model->customer_type) ? (strtolower($model->customer_type) == 'member' ? 'Member' : Yii::$app->general->getforeignkey($model->customerType, 'customer_type') ) : '';
        }, 'vAlign' => 'middle'],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code'), 'visible' => TRUE],
    ['label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type, true) : '';
        }, 'vAlign' => 'middle'],
    ['label' => Yii::t('app', 'Ref Code'), 'value' => function($model) {
            return isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type, FALSE, FALSE, true) : '';
        }, 'vAlign' => 'middle'],
    ['attribute' => 'customer_name', 'label' => Yii::t('app', 'name'), 'value' => function($model) {
            return isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type) : '';
        }, 'vAlign' => 'middle', 'filter' => TRUE, 'visible' => TRUE],
    ['attribute' => 'final_amount', 'filter' => FALSE, 'visible' => TRUE],
    ['attribute' => 'milk_amount', 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'manual_amount', 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'from_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }, 'filter' => FALSE, 'visible' => TRUE],
    ['attribute' => 'to_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }, 'filter' => FALSE, 'visible' => TRUE],
];

$grid_option = [
    'id' => 'monthly-credit-limit-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
//    'actions' => [
//        'view' => TRUE,
//        'update' => true,
//    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>