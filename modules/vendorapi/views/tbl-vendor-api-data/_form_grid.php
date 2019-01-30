<?php
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php
$attribute = [
    //'vehicle_code',
            ['attribute' => 'id','filter'=>false],
            ['attribute' => 'parent_code_other','filter'=>false],
            ['attribute' => 'parent_code','filter'=>false],
            ['attribute' => 'master_code','filter'=>false],
            ['attribute' => 'master_name','filter'=>false],
            ['attribute' => 'master_type','filter'=>false],
            ['attribute' => 'date_1','filter'=>false],
            ['attribute' => 'date_2','filter'=>false],
            ['attribute' => 'time_1','filter'=>false],
            ['attribute' => 'time_2','filter'=>false],
            ['attribute' => 'time_3','filter'=>false],
            ['attribute' => 'time_4','filter'=>false],
            ['attribute' => 'state_code','filter'=>false],
            ['attribute' => 'district_code','filter'=>false],
            ['attribute' => 'sub_district_code','filter'=>false],
            ['attribute' => 'village_code','filter'=>false],
            ['attribute' => 'hamlet_code','filter'=>false],
            ['attribute' => 'contact_first_name','filter'=>false],
            ['attribute' => 'contact_middle_name','filter'=>false],
            ['attribute' => 'contact_last_name','filter'=>false],
            ['attribute' => 'capacity','filter'=>false],
            ['attribute' => 'address','filter'=>false],
            ['attribute' => 'address_2','filter'=>false],
            ['attribute' => 'email','filter'=>false],
            ['attribute' => 'mobile_no','filter'=>false],
            ['attribute' => 'is_active','filter'=>false],
            ['attribute' => 'type_2','filter'=>false],
            ['attribute' => 'bank_name','filter'=>false],
            ['attribute' => 'branch_name','filter'=>false],
            ['attribute' => 'bank_account_no','filter'=>false],
            ['attribute' => 'ifsc','filter'=>false],
            ['attribute' => 'route_length','filter'=>false],
            ['attribute' => 'type_of_data','filter'=>false],
            ['attribute' => 'union_code','filter'=>false],
            ['attribute' => 'service_type','filter'=>false],
            ['attribute' => 'username','filter'=>false],
            ['attribute' => 'password','filter'=>false],
           ];

$grid_option = [
    'id' => 'vendor-api-data',
    'attributes' => $attribute,
    'active_column' => false,
    
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>