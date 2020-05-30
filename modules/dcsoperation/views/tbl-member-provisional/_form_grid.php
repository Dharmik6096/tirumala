<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>

<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'filter' => false],
    ['attribute' => 'member_code', 'value' => 'member_code'],
    ['attribute' => 'reference_code',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex') . $model->ex_member_code;
        },'visible' => false
    ],
    ['attribute' => 'ex_member_code', 'value' => 'ex_member_code'],
    ['attribute' => 'member_type_code', 'value' => 'memberTypeCode.member_type_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'member_name', 'value' => 'member_name'],
    ['attribute' => 'local_name', 'value' => 'local_name', 'filter' => false,'visible' => false],
    ['attribute' => 'father_name', 'value' => 'father_name','visible' => false],
    ['attribute' => 'local_father_name', 'value' => 'local_father_name', 'filter' => false,'visible' => false],
    ['attribute' => 'surname', 'value' => 'surname','visible' => false],
    ['attribute' => 'local_surname', 'value' => 'local_surname', 'filter' => false,'visible' => false],
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
    ['attribute' => 'state_code', 'value' => 'stateCode.state_name', 'filter' => false],
    ['attribute' => 'district_code', 'value' => 'districtCode.district_name', 'filter' => false],
    ['attribute' => 'sub_district_code', 'value' => 'subDistrictCode.sub_district_name', 'filter' => false],
    ['attribute' => 'village_code', 'value' => 'villageCode.village_name', 'filter' => false],
    ['attribute' => 'hamlet_code', 'value' => 'hamletCode.hamlet_name', 'filter' => false],
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
];

$grid_option = [
    'id' => 'member-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => function ($url, $model) {
            $name = $model->member_name;
            $class = ($model->is_approved == 1) ? 'link-disable' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->provisional_member_code, 'data-name' => $name];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, $options);
        },
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
