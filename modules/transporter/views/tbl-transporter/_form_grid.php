<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => true, 'filter' => false],
        ['attribute' => 'transporter_code'],
        ['attribute' => 'transporter_name'],
        ['attribute' => 'local_name', 'filter' => false],
        ['attribute' => 'registration_no', 'visible' => false, 'filter' => false],
        ['attribute' => 'address', 'value' => 'address', 'visible' => false, 'filter' => false],
        ['attribute' => 'state_code', 'value' => 'stateCode.state_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'district_code', 'value' => 'districtCode.district_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'sub_district_code', 'value' => 'subDistrictCode.sub_district_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'hamlet_code', 'value' => 'hamletCode.hamlet_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'village_code', 'value' => 'villageCode.village_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'pincode', 'visible' => false, 'filter' => false],
        ['attribute' => 'phone_no', 'visible' => false],
        ['attribute' => 'contact_person', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->defaultContactDetail, 'firstname');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'mobile_no', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->defaultContactDetail, 'mobile_no');
        }, 'visible' => true, 'filter' => false],
//    ['attribute' => 'contact_person', 'filter' => false],
    ['attribute' => 'local_contact_person', 'visible' => false, 'filter' => false],
//    ['attribute' => 'mobile_no', 'filter' => false],
    ['attribute' => 'email', 'visible' => false, 'filter' => false],
//    ['attribute' => 'bank_code', 'value' => 'bankCode.bank_name', 'visible' => false, 'filter' => false],
//    ['attribute' => 'branch_code', 'value' => 'branchCode.branch_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'bank_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->defaultBankDetail, ['bankCode'], 'bank_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'branch_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->defaultBankDetail, ['branchCode'], 'branch_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'bank_account_no', 'visible' => false, 'filter' => false],
        ['attribute' => 'ifsc', 'visible' => false, 'filter' => false],
        ['attribute' => 'gstin', 'visible' => false, 'filter' => false],
        ['attribute' => 'tds_per', 'visible' => false, 'filter' => false],
        ['attribute' => 'pan_no', 'visible' => false, 'filter' => false],
        ['attribute' => 'beneficiary_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'agreement_no', 'visible' => false, 'filter' => false],
        ['attribute' => 'declaration', 'visible' => false, 'filter' => false],
        ['attribute' => 'security_cheque_no', 'visible' => false, 'filter' => false],
        ['attribute' => 'security_amount', 'format' => Yii::$app->general->CurrencyFormat(), 'visible' => false, 'filter' => false],
        ['attribute' => 'vendor_code'],
        ['attribute' => 'billing_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->billingType, 'billing_type');
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilter('billing_type_code', $searchModel, 'billing_type_code', Yii::t('app', 'Select'))],
        [
        'attribute' => 'agreement_from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->agreement_from_date);
        }, 'visible' => false],
        [
        'attribute' => 'agreement_to_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->agreement_to_date);
        }, 'visible' => false],
];

$grid_option = [
    'id' => 'transporter-list',
    'attributes' => $attribute,
    'active_column' => TRUE,
    'actions' => [
        'view' => true,
        'update' => true,
//        'delete' => ['option' => 'transporter_name,transporter_code,tbl-transporter/delete'],
        'bank-details' => function ($url, $model) {
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-name' => $model->transporter_name, 'data-val' => $model->transporter_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Bank Details', 'class' => '' . $class];
            return GhostHtml::a('<i class="fa fa-university"></i>', ['/transporter/tbl-transporter/bank-details', 'id' => $model->transporter_code], $options);
        },
        'contact-details' => function ($url, $model) {
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-name' => $model->transporter_name, 'data-val' => $model->transporter_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Contact Details', 'class' => '' . $class];
            return GhostHtml::a('<i class="fa fas fa-user-circle"></i>', ['/transporter/tbl-transporter/contact-details', 'id' => $model->transporter_code], $options);
        },
        'active' => function ($url, $model) {
            $title = ($model->is_active == 1) ? Yii::t('app', 'Deactivate') : Yii::t('app', 'Activate');
            $icon = ($model->is_active == 1) ? 'fa fa-times' : 'fa fa-check';
            $url = ($model->is_active == 1) ? '/transporter/tbl-transporter/transporter-deactivate' : '/transporter/tbl-transporter/transporter-activate';
            $popupWindowMsg = 'Are you sure you want to ' . ($model->is_active == 1 ? 'Deactivate ' : 'Activate ') . $model->transporter_name;
            $options = [
                'data-bs-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => $title,
                'data-popup-message' => $popupWindowMsg,
                'class' => ' generalGridConfirmationPopup ',
                'data-post-url' => Url::to([$url, 'id' => $model->transporter_code])
            ];
            return GhostHtml::a_alert('<i class="fa ' . $icon . '"></i>', [[$url, 'id' => $model->transporter_code]], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
