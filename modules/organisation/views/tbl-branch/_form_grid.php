<!--<div class="grid-search">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
</div>-->
<?php
$attribute = [
    ['attribute' => 'branch_code', 'value' => 'branch_code'],
    ['attribute' => 'branch_name', 'value' => 'branch_name'],
    ['attribute' => 'local_name',],
    ['attribute' => 'address', 'value' => 'address','visible'=>false,'filter'=>false],
    ['attribute' => 'local_address','visible'=>false,'filter'=>false],
    ['attribute' => 'contact_person','visible'=>false,'filter'=>false],
    ['attribute' => 'contact_no','visible'=>false,'filter'=>false],
    ['attribute' => 'hemlet_code', 'value' => 'hamletCode.hamlet_name','visible'=>false,'filter'=>false],
    ['attribute' => 'pincode', 'value' => 'pincode','visible'=>false,'filter'=>false],
    ['attribute' => 'bank_code', 'label'=>'Bank','value' => 'bankCode.bank_name','visible'=>false,'filter'=>false],
    ['attribute' => 'state_code', 'value' => 'stateCode.state_name','visible'=>false,'filter'=>false],
    ['attribute' => 'district_code', 'value' => 'districtCode.district_name','visible'=>false,'filter'=>false],
    ['attribute' => 'sub_district_code', 'value' => 'subDistrictCode.sub_district_name','visible'=>false,'filter'=>false],
    ['attribute' => 'village_code', 'value' => 'villageCode.village_name','visible'=>false,'filter'=>false],
    ['attribute' => 'ifsc', 'value' => 'ifsc'],
    [
        'attribute' => 'valid_from',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->valid_from);
        }, 'visible' => false,'filter'=>false],
];

$grid_option = [
    'id' => 'branch-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'delete' => ['option' => 'branch_name,branch_code,tbl-branch/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>