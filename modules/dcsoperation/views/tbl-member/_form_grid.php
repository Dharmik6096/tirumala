<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
?>

<?php
$attribute = [
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'filter' => false, 'visible' => false],
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsCode, ['bmcCode'], 'ref_code');
        }, 'vAlign' => 'middle'],
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Name'), 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsCode, ['bmcCode'], 'bmc_name');
        }, 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Ref. Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'filter' => false],
        ['attribute' => 'member_code', 'value' => 'member_code'],
        ['attribute' => 'reference_code',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex') . $model->ex_member_code;
        }, 'visible' => false
    ],
        ['attribute' => 'ex_member_code', 'value' => 'ex_member_code'],
        ['attribute' => 'ref_code'],
        ['attribute' => 'member_type_code', 'value' => 'memberTypeCode.member_type_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'member_name', 'value' => 'member_name'],
        ['attribute' => 'local_name', 'value' => 'local_name', 'filter' => false],
        ['attribute' => 'father_name', 'value' => 'father_name'],
        ['attribute' => 'local_father_name', 'value' => 'local_father_name', 'filter' => false],
        ['attribute' => 'surname', 'value' => 'surname'],
        ['attribute' => 'local_surname', 'value' => 'local_surname', 'filter' => false],
        ['attribute' => 'nominee_name', 'value' => 'nominee_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'local_nominee_name', 'value' => 'local_nominee_name', 'visible' => false, 'filter' => false],
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
        ['attribute' => 'beneficiary_name', 'visible' => false, 'filter' => false],
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
        ['attribute' => 'x_col3', 'filter' => false],
        ['attribute' => 'is_verified', 'label' => Yii::t('app', 'Bank Verification'), 'value' => function($model) {
            return $model->is_verified == 1 ? 'Verified' : ( $model->is_verified == 2 ? 'Reject' : 'Pending');
        }, 'filter' => false],
        ['attribute' => 'is_contact_verified', 'label' => Yii::t('app', 'Contact Verification'), 'value' => function($model) {
            return $model->is_contact_verified == 1 ? 'Verified' : ( $model->is_contact_verified == 2 ? 'Reject' : 'Pending');
        }, 'filter' => false],
        [
        'attribute' => 'is_active', 'label' => Yii::t('app', 'Status'), 'filter' => false,
        'value' => function($model) {
            return $model->is_active == '1' ? (Yii::$app->general->getforeignkey($model->activeStatus, 'is_active') === 0 ? 'In Active' : 'Active') : 'In Active';
        },
    ],
        ['attribute' => 'rate_class',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('rate_class', $searchModel, 'rate_class'),
        'value' => function ($model) {
            return !empty($model->rate_class) ? Yii::$app->dropdown->getRecords('rate_class')['data'][$model->rate_class] : '';
        }, 'visible' => FALSE],
        ['attribute' => 'bank_remarks', 'filter' => false],
        ['attribute' => 'contact_remarks', 'filter' => false],
        ['attribute' => 'sap_farmer_code', 'filter' => TRUE],
        [
        'attribute' => 'is_dcs_member', 'filter' => FALSE, 'visible' => false,
        'value' => function($model) {
            return $model->is_dcs_member == '1' ? 'Yes' : 'No';
        },
    ],
        ['attribute' => 'employee_code', 'visible' => false, 'filter' => true],
        ['attribute' => 'employee_name', 'visible' => false, 'filter' => true],
        ['attribute' => 'region_code',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->regionCode, 'region_name');
        }, 'visible' => false, 'filter' => true
    ],
        ['attribute' => 'created_by',
        'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->userName, 'name');
        }, 'visible' => false, 'filter' => true
    ],
    ['attribute' => 'member_identity_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'witness_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'place', 'visible' => false, 'filter' => false],
    ['attribute' => 'land', 'visible' => false, 'filter' => false],
    ['attribute' => 'land_type', 'visible' => false, 'filter' => false],
    ['attribute' => 'farmer_type', 'visible' => false, 'filter' => false],
    ['attribute' => 'is_educated', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            return $model->is_educated == '1' ? 'Yes' : 'No';
        },],
    ['attribute' => 'is_cooking_gas', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            return $model->is_cooking_gas == '1' ? 'Yes' : 'No';
    },],
    ['attribute' => 'marital_status', 'visible' => false, 'filter' => false],
    ['attribute' => 'registration_no', 'visible' => false, 'filter' => false],
];

$grid_option = [
    'id' => 'member-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => function ($url, $model) {
            $name = $model->member_name;
            $class = (Yii::$app->general->getforeignkey($model->activeStatus, 'is_active') === 0 || !empty($model->provisionalMember)) ? 'link-disable' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->member_code, 'data-name' => $name];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, $options);
        },
        'view' => true,
//        'deactive' => function ($url, $model) {
//            $name = $model->member_name;
//            $class = (Yii::$app->general->getforeignkey($model->activeStatus, 'is_active') === 0) ? 'link-disable' : '';
//            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'deact-member ' . $class, 'data-val' => $model->member_code, 'data-name' => $name];
//            return GhostHtml::a_alert('<i class="fa fa-close"></i>', ['/dcsoperation/tbl-member/deactivate-user'], $options);
//        },
        //'delete' => ['option' => 'member_name,member_code,tbl-member/delete'],
        'block-unblock' => function ($url, $model) {
            $name = $model->member_name;
            $class = '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Mobile App Info', 'class' => 'block-unblock ' . $class, 'data-val' => $model->member_code, 'data-name' => $name];
            return GhostHtml::a_alert('<i class="fa fa-mobile"></i>', ['/dcsoperation/tbl-member/block-unblock'], $options);
        },
        'upload-photos' => function ($url, $model) {
            $id = $model->member_code;
            $type = 'MEMBER';
            $class = ($model->is_verified == 1) ? 'link-disable disabled' : '';
            $url = ['/dcsoperation/tbl-member/import-attachements', 'id' => $id];
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Upload', 'class' => 'upload-photo' . $class, 'data-val' => $id, 'data-name' => $type];
            return GhostHtml::a_alert('<i class="fa fa-cloud-upload"></i>', $url, $options);
        },
        'document-upload' => function ($url, $model) {
            $id = $model->member_code;
            $name = $model->member_name;
            $class = (Yii::$app->general->getforeignkey($model->activeStatus, 'is_active') === 0) ? 'link-disable' : '';
            $url = ['/dcsoperation/tbl-member/member-document-upload', 'id' => $id];
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Document Upload', 'class' => '' . $class, 'data-val' => $model->member_code, 'data-name' => $name];
            return GhostHtml::a('<i class="fa fa-link"></i>', $url, $options);
        },
        'member-details' => function ($url, $model) {
            $config = Yii::$app->general->getUnionConfiguration($model->union_code, 'allow_member_other_detail', 'PORTAL');
            $id = $model->member_code;
            $name = $model->member_name;
            $class = ( $config != 1 || (Yii::$app->general->getforeignkey($model->activeStatus, 'is_active') === 0 || !empty($model->provisionalMember))) ? 'link-disable' : '';
            $url = ['/dcsoperation/tbl-member/member-details', 'id' => $id];
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Member Other Deatils', 'class' => '' . $class, 'data-val' => $model->member_code, 'data-name' => $name];
            return GhostHtml::a('<i class="fa fa-info-circle"></i>', $url, $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['index'], true, ['CSV', 'Excel2007']);
?>
<div id="AppInformation"></div>
<div id="ImportAttachements"></div>
<?php
$script = "
$(document).ready(function(){
//    $(document).on('click','.deact-member',function(e){
//    var id= $(this).attr('data-val');
//    var name = $(this).attr('data-name');
//    bootbox.confirm({
//        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to deactivate \"'+name+'\"?</span></div></div>',
//        buttons: {
//            'cancel': {
//                            label: 'Cancel',
//                            className: 'btn btn-danger'
//              },
//            'confirm': {
//                            label: 'Ok',
//                            className: 'btn btn-primary'
//             }
//        },
//        callback: function(result) {
//            if (result) {
//              $('#loader').show();
//                 $.ajax({
//                        type: 'get',
//                        url: '" . Url::to(['deactivate-user']) . "',
//                        data:{'id':id},
//                        success: function(data) {
//                            var obj1 = $.parseJSON(data);
//                            if (obj1.status == 'success')
//                            {
//                                $.pjax.reload({container: '#member-grid'});
//                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
//                                //$.snackbar({content: 'Record successfully deleted.', timeout: 8000, style: 'successbar'});
//                            }
//                            else if (obj1.status == 'error'){
//                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
//                                //$.snackbar({content: 'Record is not deleted.', timeout: 8000, style: 'errorbar'});
//                            }
//                        },
//                        error:function(data){
//                                    //alert('Your data has not been submitted..Please try again');
//                                }
//            });
//            }
//        }
//    });
//    });
    
    $(document).on('click','.block-unblock',function(e){
        $('#pageloader').show();
        $('#loadercontent').show();
        var member_code= $(this).attr('data-val');
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/dcsoperation/tbl-member/app-information']) . "',
            data:{'member_code':member_code},
            success: function(data) {     
                $('#AppInformation').html(data);
                $('#AppInformationModal').modal('toggle'); 
                $('#loadercontent').hide();
                $('#pageloader').hide();
            },    
            error: function(data) {    
                $('#loadercontent').hide();
                $('#pageloader').hide();
            }
        });
    });
    

    $(document).on('submit','form#app-information-form-check',function(e){
//     $('form#app-information-form-check').submit(function(e) {
        var label= $('#display_label').val();
        var name= $('#member_name').val();
        e.preventDefault();
        var currentForm = this;
        bootbox.confirm({
            message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to '+label+' \"'+name+'\"?</span></div></div>',
            buttons: {
                'cancel': {
                                label: 'Cancel',
                                className: 'btn btn-danger'
                  },
                'confirm': {
                                label: 'Ok',
                                className: 'btn btn-primary'
                 }
            },
            callback: function(result) {
                if (result) {
                    $('#pageloader').show();
                    $('#loadercontent').show();
                    currentForm.submit();
                }
            }
        });
    });
     $(document).on('click','.upload-photo',function(e){
        $('#pageloader').show();
        $('#loadercontent').show();
        var code= $(this).attr('data-val');
        var type= $(this).attr('data-name');
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/dcsoperation/tbl-member/import-attachements']) . "',
            data:{'code':code,'type':type},
            success: function(data) {     
                $('#ImportAttachements').html(data);
                $('#ImportAttachementsModel').modal('toggle'); 
                $('#loadercontent').hide();
                $('#pageloader').hide();
            },    
            error: function(data) {    
                $('#loadercontent').hide();
                $('#pageloader').hide();
            }
        });
    });
});";
$this->registerJs($script, View::POS_END, 'member-index');
