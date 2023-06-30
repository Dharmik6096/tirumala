<?php

use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Html;
use kartik\grid\GridView;
?>

<div class="grid-search">

</div>
<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => 'union_code'],
    ['attribute' => 'union_code_ex', 'value' => 'union_code_ex'],
    ['attribute' => 'union_name', 'value' => 'union_name'],
    ['attribute' => 'local_name',],
    ['attribute' => 'union_short_name',],
    ['attribute' => 'upi_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'gst_no', 'visible' => false, 'filter' => false],
    // ['attribute' => 'contact_person', 'value' => 'contact_person','filter'=>false],
    ['attribute' => 'address', 'visible' => false, 'filter' => false],
    ['attribute' => 'local_address', 'visible' => false, 'filter' => false],
    //['attribute' => 'bank_account_no', 'visible' => false,'filter'=>false],
    ['attribute' => 'city', 'visible' => false, 'filter' => false],
    //['attribute' => 'contact_person_email', 'visible' => false,'filter'=>false],
    //['attribute' => 'contact_person_mobile_no', 'visible' => false,'filter'=>false],
    ['attribute' => 'contact_person_pan_no', 'visible' => false, 'filter' => false],
//    ['attribute' => 'contact_person_phone_no', 'visible' => false,'filter'=>false],
    ['attribute' => 'phone_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'fax_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'pincode', 'visible' => false, 'filter' => false],
    [
        'attribute' => 'registration_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->registration_date);
        }, 'visible' => false, 'filter' => false],
    [
        'attribute' => 'valid_from',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->valid_from);
        }, 'visible' => false, 'filter' => false],
    ['attribute' => 'registration_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'federation_code', 'value' => 'federationCode.federation_name', 'visible' => false, 'filter' => false],
    //['attribute' => 'bank_code', 'value' => 'bankCode.bank_name', 'visible' => false,'filter'=>false],
    //['attribute' => 'branch_code', 'value' => 'branchCode.branch_name', 'visible' => false,'filter'=>false],
    ['attribute' => 'state_code', 'value' => 'stateCode.state_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'district_code', 'value' => 'districtCode.district_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'sub_district_code', 'value' => 'subDistrictCode.sub_district_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'village_code', 'value' => 'villageCode.village_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'hamlet_code', 'value' => 'hamletCode.hamlet_name', 'visible' => false, 'filter' => false],
// Contact Detail
    ['label' => 'Contact Person', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->union_code, 'union');
            isset($detail->firstname) ? $detail = $detail->firstname . ' ' . $detail->lastname . ' ' . $detail->surname : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Contact Person Hindi Name', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->union_code, 'union');
            isset($detail->local_firstname) ? $detail = $detail->local_firstname . ' ' . $detail->local_lastname . ' ' . $detail->local_surname : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Email', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->union_code, 'union');
            isset($detail->email) ? $detail = $detail->email : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Mobile No', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->union_code, 'union');
            isset($detail->mobile_no) ? $detail = $detail->mobile_no : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Department', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->union_code, 'union');
            isset($detail->department) ? $detail = $detail->department : $detail = '';
            return $detail;
        }
    ],
// Bank Detail
    ['label' => 'Bank', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->union_code, 'union');
            isset($detail->bankCode) ? $detail = $detail->bankCode->bank_name : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Branch', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->union_code, 'union');
            isset($detail->branchCode) ? $detail = $detail->branchCode->branch_name : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Bank Account No', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->union_code, 'union');
            isset($detail->bank_account_no) ? $detail = $detail->bank_account_no : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'IFSC', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->union_code, 'union');
            isset($detail->ifsc) ? $detail = $detail->ifsc : $detail = '';
            return $detail;
        }
    ],
];

$grid_option = [
    'id' => 'union-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'update' => true,
        'delete' => ['option' => 'union_name,union_code,tbl-unions/delete'],
        'mapping' => function ($url, $model) {
            $options = ['data-name' => $model->union_name, 'data-val' => $model->union_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'District Mapping'];
            return GhostHtml::a('<i class="fa fa-link"></i>', ['/organisation/tbl-unions/map-districts', 'id' => $model->union_code], $options);
        },
        'bank-details' => function ($url, $model) {
            $options = ['data-name' => $model->union_name, 'data-val' => $model->union_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Bank Details'];
            return GhostHtml::a('<i class="fa fa-university"></i>', ['/organisation/tbl-unions/bank-details', 'id' => $model->union_code], $options);
        },
        'contact-details' => function ($url, $model) {
            $options = ['data-name' => $model->union_name, 'data-val' => $model->union_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Contact Details'];
            return GhostHtml::a('<i class="fa fas fa-user-circle"></i>', ['/organisation/tbl-unions/contact-details', 'id' => $model->union_code], $options);
        },
        'union-config' => function ($url, $model) {
            $options = ['data-name' => $model->union_name, 'data-val' => $model->union_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Union Configurations')];
            return GhostHtml::a('<i class="fa fa-cog"></i>', ['/configuration/tbl-config/create', 'id' => $model->union_code], $options);
        },
        'control-mapping' => function ($url, $model) {
            $options = ['data-name' => $model->union_name, 'data-val' => $model->union_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Control Mapping')];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/configuration/tbl-config-mapping/index', 'id' => $model->union_code], $options);
        },
        'payment-config' => function ($url, $model) {
            $options = ['data-name' => $model->union_name, 'data-val' => $model->union_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Payment Configurations')];
            return GhostHtml::a('<i class="fa fa fa-money-bill"></i>', ['/configuration/tbl-config-mapping/index', 'id' => $model->union_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>