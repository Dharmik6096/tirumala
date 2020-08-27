<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => FALSE],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => FALSE],
    ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'filter' => FALSE],
    ['attribute' => 'customer_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        }, 'filter' => Yii::$app->dropdown->dropdownfilter('customer_type', $searchModel, 'customer_type', Yii::t('app', 'Select'))],
    ['attribute' => 'customer_code'],
    ['attribute' => 'customer_code_ex'],
    ['attribute' => 'customer_name'],
    ['attribute' => 'local_name', 'filter' => FALSE],
    ['attribute' => 'gst_no',],
    ['attribute' => 'address', 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'local_address', 'filter' => FALSE, 'visible' => FALSE],
];

$grid_option = [
    'id' => 'customer-master-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
        'bank-details' => function ($url, $model) {
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-name' => $model->customer_name, 'data-val' => $model->customer_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Bank Details', 'class' => '' . $class];
            return GhostHtml::a('<i class="fa fa-university"></i>', ['/organisation/tbl-customer-master/bank-details', 'id' => $model->customer_code], $options);
        },
        'contact-details' => function ($url, $model) {
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-name' => $model->customer_name, 'data-val' => $model->customer_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Contact Details', 'class' => '' . $class];
            return GhostHtml::a('<i class="fa fa-user-circle-o"></i>', ['/organisation/tbl-customer-master/contact-details', 'id' => $model->customer_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
