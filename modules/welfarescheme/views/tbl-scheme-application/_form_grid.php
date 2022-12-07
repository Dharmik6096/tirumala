<?php

use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'application_id'],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => false],
        ['attribute' => 'customer_type', 'value' => function($model) {
            return isset($model->customer_type) ? (strtolower($model->customer_type) == 'member' ? 'Member' : Yii::$app->general->getforeignkey($model->customerType, 'customer_desc') ) : '';
        },],
    //  ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code')],
    ['label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type, true) : '';
        }],
        ['label' => Yii::t('app', 'Code'), 'value' => function($model) {
            return isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type, FALSE, FALSE, true) : '';
        }],
        ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type) : '';
        }],
        ['attribute' => 'scheme_id', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->schemeId, 'scheme_name');
        }],
        ['attribute' => 'application_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->application_date);
        }, 'filter' => FALSE],
        ['attribute' => 'min_pouring_day',
        'label' => Yii::t('app', 'Day(min/act)'),
        'value' => function($model) {
            return $model->min_pouring_day . '/' . $model->actual_pouring_day;
        }, 'filter' => false],
        ['attribute' => 'min_pouring_qty',
        'label' => Yii::t('app', 'Qty(min/act)'),
        'value' => function($model) {
            return $model->min_pouring_qty . '/' . $model->actual_pouring_qty;
        }, 'filter' => false],
        ['attribute' => 'scheme_value',
        'label' => Yii::t('app', 'Value(Scheme/Approved)'),
        'value' => function($model) {
            return $model->scheme_value . '/' . $model->approved_value;
        }, 'filter' => false],
        ['attribute' => 'application_status', 'filter' => FALSE],
        ['attribute' => 'status_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->status_date);
        }, 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'status_by', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->statusBy, 'name');
        }, 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'status_remarks', 'filter' => false, 'visible' => FALSE],
    'remarks',
];

$grid_option = [
    'id' => 'tbl-scheme-application-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => true,
        'add-document' => function ($url, $model) {
            $disable = ($model->application_status == 'pending') ? '' : 'disabled';
            $options = ['title' => Yii::t('app', 'Add Document'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-file"></i>', ['/welfarescheme/tbl-scheme-application/add-document', 'id' => $model->application_id], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
