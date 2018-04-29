<?php

use yii\helpers\Html;
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
    ['attribute' => 'security_amount', 'format' => Yii::$app->general->CurrencyFormat(),'visible' => false, 'filter' => false],
];

$grid_option = [
    'id' => 'transporter-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
//        'delete' => ['option' => 'transporter_name,transporter_code,tbl-transporter/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
