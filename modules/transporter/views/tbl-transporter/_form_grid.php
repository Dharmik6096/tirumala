<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<div class="grid-search">
    <?php
    echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>
<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => true, 'filter' => false],
//    ['attribute' => 'transporter_code'],
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
    ['attribute' => 'phone_no'],
    ['attribute' => 'contact_person', 'visible' => false, 'filter' => false],
    ['attribute' => 'local_contact_person', 'visible' => false, 'filter' => false],
    ['attribute' => 'mobile_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'email', 'visible' => false, 'filter' => false],
    ['attribute' => 'bank_code', 'value' => 'bankCode.bank_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'branch_code', 'value' => 'branchCode.branch_name', 'visible' => false, 'filter' => false],
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
];

$grid_option = [
    'id' => 'transporter-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
//        'delete' => ['option' => 'transporter_name,transporter_code,tbl-transporter/delete'],
        'bank-details' => function ($url, $model) {
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-name' => $model->transporter_name, 'data-val' => $model->transporter_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Bank Details', 'class' => '' . $class];
            return GhostHtml::a('<i class="fa fa-university"></i>', ['/transporter/tbl-transporter/bank-details', 'id' => $model->transporter_code], $options);
        },
        'contact-details' => function ($url, $model) {
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-name' => $model->transporter_name, 'data-val' => $model->transporter_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Contact Details', 'class' => '' . $class];
            return GhostHtml::a('<i class="fa fa-user-circle-o"></i>', ['/transporter/tbl-transporter/contact-details', 'id' => $model->transporter_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
