<?php
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
?>
<div class="grid-search">
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
</div>
<?php
$attribute = [
    ['attribute' => 'federation_code', 'value' => 'federation_code'],
    ['attribute' => 'federation_code_ex', 'value' => 'federation_code_ex','visible' => false,'filter'=>false],
    ['attribute' => 'federation_name', 'value' => 'federation_name'],
    ['attribute' => 'local_name'],
    ['attribute' => 'contact_person', 'value' => 'contact_person'],
    ['attribute' => 'state_code', 'value' => 'stateCode.state_name'],
    ['attribute' => 'address', 'visible' => false,'filter'=>false],
    ['attribute' => 'local_address', 'visible' => false,'filter'=>false],
    ['attribute' => 'bank_account_no', 'visible' => false,'filter'=>false],
    ['attribute' => 'city', 'visible' => false,'filter'=>false],
    ['attribute' => 'contact_person_email', 'visible' => false,'filter'=>false],
    ['attribute' => 'contact_person_mobile_no', 'visible' => false,'filter'=>false],
    ['attribute' => 'contact_person_pan_no', 'visible' => false,'filter'=>false],
    ['attribute' => 'contact_person_phone_no', 'visible' => false,'filter'=>false],
    ['attribute' => 'ifsc', 'visible' => false,'filter'=>false],
    ['attribute' => 'phone_no', 'visible' => false,'filter'=>false],
    ['attribute' => 'fax_no', 'visible' => false,'filter'=>false],
    ['attribute' => 'pincode', 'visible' => false,'filter'=>false],
    [
        'attribute' => 'registration_date',
        'visible' => false,'filter'=>false,
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        //'filter' => Yii::$app->controls->search_date($searchModel, 'registration_date'),
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->registration_date);
        }],
    ['attribute' => 'registration_no', 'visible' => false,'filter'=>false],
    ['attribute' => 'bank_code', 'value' => 'bankCode.bank_name', 'visible' => false,'filter'=>false],
    ['attribute' => 'branch_code', 'value' => 'branchCode.branch_name', 'visible' => false,'filter'=>false],
    ['attribute' => 'state_code', 'value' => 'stateCode.state_name', 'visible' => false,'filter'=>false],
    ['attribute' => 'district_code', 'value' => 'districtCode.district_name', 'visible' => false,'filter'=>false],
    ['attribute' => 'sub_district_code', 'value' => 'subDistrictCode.sub_district_name', 'visible' => false,'filter'=>false],
    ['attribute' => 'village_code', 'value' => 'villageCode.village_name', 'visible' => false,'filter'=>false],
    ['attribute' => 'hamlet_code', 'value' => 'hamletCode.hamlet_name', 'visible' => false,'filter'=>false],
];

$grid_option = [
    'id' => 'federations-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'update' => true,
        'delete' => ['option' => 'federation_name,federation_code,tbl-federations/delete'],
        //  'mapping'=> ['option' => 'federation_name,federation_code,/organisation/tbl-federations/map-states'],
//        'mapping' => function ($url, $model) {
//            $options = ['data-name' => $model->federation_name, 'data-val' => $model->federation_code, 'data-toggle' => 'tooltip' , 'data-placement' => 'top', 'data-original-title' => 'State Mapping'];
//            return GhostHtml::a('<i class="fa fa-link"></i>', ['/organisation/tbl-federations/map-states', 'id' => $model->federation_code], $options);
//        }
//                       ,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>