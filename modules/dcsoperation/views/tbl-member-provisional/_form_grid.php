<?php

use app\components\GeneralFunctions;
use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
?>

<?php
$attribute = [
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'activityStatus', 'label' => '', 'visible' => true, 'value' => function ($model) {
            return Yii::$app->general->generateActivityStatus($model, 'updated_at');
        }, 'format' => 'raw', 'contentOptions' => ['class' => 'sticky-column']],
        ['attribute' => 'bmc_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->tblDcsBmc, 'ref_code');
        }, 'filter' => false],
        ['attribute' => 'bmc_name', 'value' => 'tblDcsBmc.bmc_name', 'filter' => false],
        ['attribute' => 'created_at', 'vAlign' => 'middle', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->created_at);
        }, 'filter' => false],
        ['attribute' => 'approved_at', 'filter' => true,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->approved_at);
        },
    ],
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
        ['attribute' => 'surname', 'value' => 'surname', 'visible' => true],
        ['attribute' => 'local_name', 'value' => 'local_name', 'filter' => false, 'visible' => false],
        ['attribute' => 'father_name', 'value' => 'father_name', 'visible' => false],
        ['attribute' => 'local_father_name', 'value' => 'local_father_name', 'filter' => false, 'visible' => false],
        ['attribute' => 'local_surname', 'value' => 'local_surname', 'filter' => false, 'visible' => false],
        ['attribute' => 'nominee_name', 'value' => function ($model) {
            return !empty($model->nominee_name) ? $model->nominee_name : Yii::$app->general->getforeignkey($model->familyDetail, 'family_member_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'local_nominee_name', 'value' => 'local_nominee_name', 'visible' => false, 'filter' => false,],
        ['attribute' => 'dob', 'visible' => false, 'filter' => false],
        ['attribute' => 'bloodgroup_code', 'value' => 'bloodGroupCode.blood_group', 'visible' => false, 'filter' => false],
        ['attribute' => 'gender_code', 'value' => 'genderCode.gender', 'visible' => false, 'filter' => false],
        ['attribute' => 'qualification_code', 'value' => 'qualificationCode.qualification_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'caste_category_code', 'value' => 'casteCategoryCode.caste_category_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'religion_code', 'value' => 'religionCode.religion', 'visible' => false, 'filter' => false],
        ['attribute' => 'nominee_relation', 'value' => function ($model) {
            return !empty($model->nominee_relation) ? Yii::$app->general->getforeignkey($model->relationship, 'relationship') : Yii::$app->general->getmultiforeignkey($model->familyDetail, ['relationship'], 'relationship');
        }, 'visible' => true, 'filter' => false],
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
        ['attribute' => 'bank_account_no', 'visible' => false, 'filter' => false, 'contentOptions' => ['cellFormat' => DataType::TYPE_STRING]],
        ['attribute' => 'ifsc', 'visible' => false, 'filter' => false],
        ['attribute' => 'pan_no', 'visible' => false, 'filter' => false],
        ['attribute' => 'adhar_no', 'visible' => false, 'filter' => false, 'contentOptions' => ['cellFormat' => DataType::TYPE_STRING]],
        ['attribute' => 'annual_income', 'visible' => false, 'filter' => false],
        ['attribute' => 'payment_mode', 'visible' => false, 'filter' => false],
        ['attribute' => 'registration_date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->registration_date);
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'member_class', 'value' => function($model) {
            return ($model->member_class == 1) ? 'APL' : ($model->member_class == 2 ? 'BPL' : '');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'application_no', 'filter' => true],
        ['attribute' => 'is_approved', 'value' => function($model) {
            return $model->is_approved == 1 ? 'Approved' : 'Pending';
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'provisional_from'],
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
        ['attribute' => 'payment_type', 'value' => function($model) {
            return (!empty($model['shareCode']->mode_of_payment) && $model['shareCode']->mode_of_payment != null) ? Yii::$app->dropdown->getRecords('mode_of_payment')['data'][$model['shareCode']->mode_of_payment] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('mode_of_payment', $searchModel, 'payment_type')],
        ['attribute' => 'recipt_ref_no', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->shareCode, 'ref_no');
        }, 'visible' => true, 'filter' => true
    ],
        ['attribute' => 'provisional_status',
        'filter' => (!$pending_approval) ? Yii::$app->dropdown->dropdownfilterStatic('provisional_status', $searchModel, 'provisional_status') : false,
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('provisional_status')['data'][$model->provisional_status]) ? Yii::$app->dropdown->getRecords('provisional_status')['data'][$model->provisional_status] : '';
        }],
        ['attribute' => 'sap_farmer_code', 'visible' => true, 'filter' => false],
        ['attribute' => 'is_verify', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('verified_flag', $model, 'is_verify');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'is_contact_verified', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('verified_flag', $model, 'is_contact_verified');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'is_email_verify', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('verified_flag', $model, 'is_email_verify');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'is_aadhar_verify', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('verified_flag', $model, 'is_aadhar_verify');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'data_post_status',
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status]) ? Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status] : 'Pending';
        }, 'filter' => false, 'visible' => false],
        ['attribute' => 'picked_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->picked_datetime, 'php:d-m-Y H:i:s');
        }, 'filter' => FALSE, 'visible' => false],
        ['attribute' => 'response_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->response_datetime, 'php:d-m-Y H:i:s');
        }, 'filter' => FALSE, 'visible' => false],
        ['attribute' => 'resp_desc', 'filter' => FALSE, 'visible' => false],
        ['attribute' => 'receipt_scan_copy', 'visible' => false],
];
$gridId = 'member-grid';
$grid_option = [
    'id' => $gridId,
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'approve_view' => function($url, $model) use ($pending_approval) {
            if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/view-approval')) {
                $unionCode = !empty($model->union_code) ? $model->union_code : ($model->dcsCode->union_code ?? '');
                $config = Yii::$app->general->getUnionConfiguration($unionCode, 'workflow_require', 'PORTAL');
                if ($config == 1) {
                    $class = (!$pending_approval) ? 'link-disable' : '';
                    $icon = '<i class="fa fa-check-square"></i>';
                    $url = ['/dcsoperation/tbl-member-provisional/view-approval', 'id' => $model->process_approval_code];
                    $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Approve ' . Yii::t('yii', 'Member') . ' Provisional With View', 'class' => '' . $class];
                    return Html::a($icon, $url, $options);
                }
            }
            return '';
        },
        'approve' => function($url, $model) use ($pending_approval) {
            if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/edit-approval')) {
                $unionCode = !empty($model->union_code) ? $model->union_code : ($model->dcsCode->union_code ?? '');
                $config = Yii::$app->general->getUnionConfiguration($unionCode, 'workflow_require', 'PORTAL');
                if ($config == 1) {
                    $class = (!$pending_approval) ? 'link-disable' : '';
                    $icon = '<i class="fa fa-pen-square"></i>';
                    $url = ['/dcsoperation/tbl-member-provisional/edit-approval', 'id' => $model->process_approval_code];
                    $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Approve ' . Yii::t('yii', 'Member') . ' Provisional With Edit', 'class' => '' . $class];
                    return Html::a($icon, $url, $options);
                }
            }
            return '';
        },
        'update' => function ($url, $model)use ($pending_approval) {
            if (Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'workflow_require', 'PORTAL') == 0) {
                $name = $model->member_name;
                $class = ($model->is_approved == 1) ? 'link-disable' : '';
                $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->provisional_member_code];
                return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', $url, $options);
            } else {
                $class = '';
                if (!$pending_approval) {
                    $class = ($model->is_active === 0 || ($model->provisional_status != 'Pending' && $model->provisional_status != 'Reroute')) ? 'link-disable' : '';
                }
                $name = $model->member_name;
                $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->member_code, 'data-name' => $name];
                return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', $url, $options);
            }
            $name = $model->member_name;
            $class = ($model->is_approved == 1) ? 'link-disable' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->provisional_member_code];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', $url, $options);
        },
        'views' => function($url, $model) use ($pending_approval) {
            $config = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'workflow_require', 'PORTAL');
            if ($config == 1) {
                if ($pending_approval) {
                    if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approve-member')) {
                        $icon = '<i class="fa fa-check"></i>';
                        $url = ['/dcsoperation/tbl-member-provisional/approve-member', 'id' => $model->process_approval_code];
                        $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Approve Member'];
                        return Html::a($icon, $url, $options);
                    }
                    return '';
                } else {
                    $icon = '<i class="fa fa-eye"></i>';
                    $url = ['/dcsoperation/tbl-member-provisional/view', 'id' => $model->provisional_member_code];
                    $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Provisional Member View'];
                    return Html::a($icon, $url, $options);
                }
            } else {
                $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Provisional Member View'];
                return Html::a('<i class="fa fa-eye"></i>', ['/dcsoperation/tbl-member-provisional/view', 'id' => $model->provisional_member_code], $options);
            }
        },
        'milk_collection' => function ($url, $model) {
            $class = ($model->is_approved == 1 || strtolower($model->provisional_from) != 'collection') ? 'link-disable' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Milk Collection', 'class' => 'view_data ' . $class, 'data-dcs_code' => $model->dcs_code, 'data-pro_ex_mem_code' => $model->pro_ex_member_code, 'data-name' => $model->member_name];
            return GhostHtml::a_alert('<i class="fa fa-list"></i>', ['/dcsoperation/tbl-member-provisional/provisional-milk-collection-list'], $options);
        },
        'document-upload' => function ($url, $model) use ($pending_approval) {
            if (!$pending_approval) {
                $disable = ($model->provisional_status == 'Pending' || $model->provisional_status == 'Reroute') ? '' : 'disabled';
                $options = ['title' => Yii::t('app', 'Add Document'), 'class' => $disable];
                return GhostHtml::a('<i class="fa fa-file"></i>', ['/dcsoperation/tbl-member-provisional/document-upload', 'id' => $model->provisional_member_code], $options);
            }
        },
        'report' => function ($url, $model) use ($pending_approval) {
            if (!$pending_approval) {
                $disable = in_array(strtolower($model->provisional_status), ['approve', 'register', 'inprogress', 'pending', 'reject', 'Reroute']) ? '' : 'disabled';
                $options = ['title' => Yii::t('app', 'View Report'), 'class' => $disable, 'target' => '_blank'];
                // return GhostHtml::a('<i class="fa fa-file-pdf-o"></i>', ['/jasperreports/default/provisional-member-register'], $options);
                return GhostHtml::a('<i class="fa fa-file-pdf"></i>', ['/jasperreports/default/provisional-member-register', 'code' => $model->provisional_member_code], $options);
            }
        },
        'repush' => function ($url, $model) use ($gridId) {
            return Yii::$app->general->createRePushLink($url, $model, $gridId, 'provisional_member_code');
        },
        'delete' => ['option' => 'member_name,provisional_member_code,tbl-member-provisional/delete,checkDelete()'],
        'view_attachment' => function($url, $model) {
            $options = ['data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'title' => 'View Attachment'];
            return Html::a('<i class="fa fa-paperclip"></i>', ['view-attachment', 'id' => $model->provisional_member_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['index'], TRUE, ['Csv']);
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
