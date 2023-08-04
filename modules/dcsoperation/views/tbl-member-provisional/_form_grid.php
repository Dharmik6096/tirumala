<?php

use app\components\GeneralFunctions;
use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>

<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => 'bmc_code', 'filter' => false],
    ['attribute' => 'bmc_name', 'value' => 'tblDcsBmc.bmc_name', 'filter' => false],
    ['attribute' => 'society_code', 'value' => 'dcs_code', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'filter' => false],
    ['attribute' => 'member_code', 'value' => 'member_code'],
    ['attribute' => 'reference_code',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex') . $model->ex_member_code;
        }, 'visible' => false
    ],
    ['attribute' => 'pro_ex_member_code', 'value' => 'pro_ex_member_code'],
    ['attribute' => 'ex_member_code', 'value' => 'ex_member_code'],
    ['attribute' => 'member_type_code', 'value' => 'memberTypeCode.member_type_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'member_name', 'value' => 'member_name'],
    ['attribute' => 'local_name', 'value' => 'local_name', 'filter' => false, 'visible' => false],
    ['attribute' => 'father_name', 'value' => 'father_name', 'visible' => false],
    ['attribute' => 'local_father_name', 'value' => 'local_father_name', 'filter' => false, 'visible' => false],
    ['attribute' => 'surname', 'value' => 'surname', 'visible' => false],
    ['attribute' => 'local_surname', 'value' => 'local_surname', 'filter' => false, 'visible' => false],
    ['attribute' => 'nominee_name', 'value' => 'nominee_name', 'visible' => false, 'filter' => false,],
    ['attribute' => 'local_nominee_name', 'value' => 'local_nominee_name', 'visible' => false, 'filter' => false,],
    ['attribute' => 'dob', 'visible' => false, 'filter' => false],
    ['attribute' => 'bloodgroup_code', 'value' => 'bloodGroupCode.blood_group', 'visible' => false, 'filter' => false],
    ['attribute' => 'gender_code', 'value' => 'genderCode.gender', 'visible' => false, 'filter' => false],
    ['attribute' => 'qualification_code', 'value' => 'qualificationCode.qualification_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'caste_category_code', 'value' => 'casteCategoryCode.caste_category_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'religion_code', 'value' => 'religionCode.religion', 'visible' => false, 'filter' => false],
    ['attribute' => 'nominee_relation', 'value' => 'relationship.relationship', 'visible' => false, 'filter' => false],
    ['attribute' => 'voter_id', 'visible' => false, 'filter' => false],
    ['attribute' => 'animal_type_code', 'value' => 'animalTypeCode.animal_type_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'no_of_buffalo', 'visible' => false, 'filter' => false],
    ['attribute' => 'no_of_cow_cross', 'visible' => false, 'filter' => false],
    ['attribute' => 'no_of_cow_ind', 'visible' => false, 'filter' => false],
    ['attribute' => 'total_animals', 'visible' => false, 'filter' => false],
    // ['attribute' => 'land_class', 'visible' => false, 'filter' => false],
    ['attribute' => 'total_land', 'visible' => false, 'filter' => false],
    ['attribute' => 'address', 'value' => 'address', 'visible' => false, 'filter' => false],
    ['attribute' => 'local_address', 'visible' => false, 'filter' => false],
    ['attribute' => 'state_code', 'value' => 'stateCode.state_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'district_code', 'value' => 'districtCode.district_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'sub_district_code', 'value' => 'subDistrictCode.sub_district_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'village_code', 'value' => 'villageCode.village_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'hamlet_code', 'value' => 'hamletCode.hamlet_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'pincode', 'visible' => false, 'filter' => false],
    ['attribute' => 'mobile_no', 'visible' => TRUE, 'filter' => true],
    ['attribute' => 'email', 'visible' => false, 'filter' => false],
    ['attribute' => 'bank_code', 'value' => 'bankCode.bank_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'branch_code', 'value' => 'branchCode.branch_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'bank_name', 'value' => 'bank_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'branch_name', 'value' => 'branch_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'bank_account_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'ifsc', 'visible' => false, 'filter' => false],
    ['attribute' => 'pan_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'adhar_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'annual_income', 'visible' => false, 'filter' => false],
    ['attribute' => 'payment_mode', 'visible' => false, 'filter' => false],
    ['attribute' => 'registration_date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->registration_date);
        }, 'visible' => false, 'filter' => false],
    ['attribute' => 'member_class', 'value' => function($model) {
            return ($model->member_class == 1) ? 'APL' : ($model->member_class == 2 ? 'BPL' : '');
        }, 'visible' => false, 'filter' => false],
    ['attribute' => 'is_approved', 'value' => function($model) {
            return $model->is_approved == 1 ? 'Approved' : 'Pending';
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'provisional_from'],
];

$grid_option = [
    'id' => 'member-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => function ($url, $model) {
            $name = $model->member_name;
            $class = ($model->is_approved == 1) ? 'link-disable' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->provisional_member_code];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', $url, $options);
        },
        'view' => true,
        'milk_collection' => function ($url, $model) {
            $class = ($model->is_approved == 1 || strtolower($model->provisional_from) != 'collection') ? 'link-disable' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Milk Collection', 'class' => 'view_data ' . $class, 'data-dcs_code' => $model->dcs_code, 'data-pro_ex_mem_code' => $model->pro_ex_member_code, 'data-name' => $model->member_name];
            return GhostHtml::a_alert('<i class="fa fa-list"></i>', ['/dcsoperation/tbl-member-provisional/provisional-milk-collection-list'], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<div id="milkCollectionDetails"></div>
<?php
$script = "
$(document).ready(function(){
$(document).on('click','.view_data',function(e){
    $('#pageloader').show();
    $('#loadercontent').show();
    var dcs_code= $(this).attr('data-dcs_code');
    var pro_ex_mem_code= $(this).attr('data-pro_ex_mem_code');
    $.ajax({
        type: 'post',
        url: '" . Url::to(['/dcsoperation/tbl-member-provisional/provisional-milk-collection-list']) . "',
        data:{'member_code':dcs_code+pro_ex_mem_code},
        success: function(data) {     
            $('#milkCollectionDetails').html(data);
            $('#provisionalMilkCollection').modal('toggle'); 
            $('#loadercontent').hide();
            $('#pageloader').hide();
        },    
        error: function(data) {    
            $('#loadercontent').hide();
            $('#pageloader').hide();
        }
    });
});
});
";

$this->registerJs($script, View::POS_END, 'provisional-data');
?>
