<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<div class="grid-search">
<?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>
<?php

$attribute = [
    ['attribute' => 'sub_center_code', 'value' => 'sub_center_code', 'vAlign' => 'middle'],
    ['attribute' => 'sub_center_name', 'value' => 'sub_center_name', 'vAlign' => 'middle'],
    ['attribute' => 'local_name', 'vAlign' => 'middle',],
    ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'vAlign' => 'middle'],
    [
        'attribute' => 'is_main_dcs',
        'vAlign' => 'middle',
        'filter' => Html::activeDropDownList($searchModel, 'is_main_dcs', [1=>'Yes',0=>'No'],['class'=>'form-control','prompt'=>'Select']),
        'value' => function($model) {return ($model->is_main_dcs==1)?'Yes':'No';}
],
    ['attribute' => 'contact_person', 'value' => 'contact_person', 'vAlign' => 'middle'],
    ['attribute' => 'address', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'local_address', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'bank_account_no', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'contact_person_email', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'contact_person_mobile_no', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'phone_no', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'pincode', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
        ['attribute' => 'branch_code', 'value' => 'branchCode.branch_name', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
        ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
        ['attribute' => 'route_code', 'value' => 'routeCode.route_name', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
//        ['attribute' => 'dcs_type_code','dcsTypeCode.dcs_type_name', 'vAlign' => 'middle','visible'=>false],
        ['attribute' => 'state_code', 'value' => 'stateCode.state_name', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'district_code', 'value' => 'districtCode.district_name', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'sub_district_code', 'value' => 'subDistrictCode.sub_district_name', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'village_code', 'value' => 'villageCode.village_name', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
    ['attribute' => 'hamlet_code', 'value' => 'hamletCode.hamlet_name', 'vAlign' => 'middle','visible'=>false,'filter'=>false],
];

$grid_option = [
    'id' => 'sub-center-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'delete' => ['option' => 'sub_center_name,sub_center_code,tbl-sub-center/delete'],
        'bmc_mapping' => function ($url, $model) {
                $options = ['data-name' => $model->sub_center_name, 'data-val' => $model->sub_center_code,'title'=>'Add Bmc'];
                return GhostHtml::a('<span class="fas fa-plus"></span>', ['/organisation/tbl-dcs-bmc/index', 'dcs' => $model->dcs_code,'dcsname'=>$model->dcsCode->dcs_name,'subcenter'=> $model->sub_center_code,'subname'=>$model->sub_center_name,'type'=>'Sub Center'], $options);
        },
        'miscellaneous' => function ($url, $model) {
            $options = ['data-name' => $model->sub_center_name, 'data-val' => $model->sub_center_code,'title'=>'Add Miscellaneous'];
            return GhostHtml::a('<span class="glyphicon glyphicon-pushpin"></span>', ['/organisation/tbl-dcs-subcenter-misc/index', 'id' => $model->sub_center_code, 'name' => $model->sub_center_name,'type'=>'subcenter'], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>