<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use app\modules\installation\models\TblAndroidInstallationDetails;
?>
<?php
$attribute = [
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'route_code', 'label' => Yii::t('app', 'Route Code'), 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => 'dcs_code'],
        ['attribute' => 'dcs_code_ex', 'value' => 'dcs_code_ex'],
        ['attribute' => 'ref_code'],
        ['attribute' => 'dcs_name', 'value' => 'dcs_name'],
        ['attribute' => 'dcs_short_name', 'value' => 'dcs_short_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'local_name', 'filter' => false],
        ['attribute' => 'local_short_name', 'filter' => false],
        ['attribute' => 'mobile_no', 'label' => 'Mobile No',
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->dcs_code, 'society');
            isset($detail->mobile_no) ? $detail = $detail->mobile_no : $detail = '';
            return $detail;
        }
    ],
        ['attribute' => 'phone_no', 'visible' => false, 'filter' => false],
        ['attribute' => 'registration_code', 'visible' => false, 'filter' => false],
        [
        'attribute' => 'registration_date', 'visible' => false, 'filter' => true,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->registration_date);
        },],
        [
        'attribute' => 'valid_from',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->valid_from);
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'dcs_type_code', 'value' => 'dcsTypeCode.dcs_type_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'organisation_type_code', 'value' => 'organisationTypeCode.organisation_type', 'visible' => false, 'filter' => false],
        ['attribute' => 'scheme_type_code', 'value' => 'schemeTypeCode.scheme_type', 'visible' => false, 'filter' => false],
        [
        'attribute' => 'effective_date', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->effective_date);
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'pan_no', 'visible' => false, 'filter' => false],
        ['attribute' => 'secretory_info', 'visible' => false, 'filter' => false],
        ['attribute' => 'gst_no', 'visible' => false, 'filter' => false],
        ['attribute' => 'fssi', 'visible' => false, 'filter' => false],
        ['attribute' => 'fssi_expiry_date', 'filter' => false,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->fssi_expiry_date);
        }, 'filter' => false],
        ['attribute' => 'address', 'value' => 'address', 'visible' => false, 'filter' => false],
        ['attribute' => 'local_address', 'visible' => false, 'filter' => false],
        ['attribute' => 'state_code', 'value' => 'stateCode.state_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'district_code', 'value' => 'districtCode.district_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'sub_district_code', 'value' => 'subDistrictCode.sub_district_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'village_code', 'value' => 'villageCode.village_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'hamlet_code', 'value' => 'hamletCode.hamlet_name', 'visible' => false, 'filter' => false],
        ['attribute' => 'pincode', 'visible' => false, 'filter' => false],
        [
        'attribute' => 'allow_multi_family_member', 'visible' => false, 'filter' => false,
        'filter' => Html::activeDropDownList($searchModel, 'allow_multi_family_member', [1 => 'Yes', 0 => 'No'], ['class' => 'form-control', 'prompt' => 'Select']),
        'value' => function($model) {
            return ($model->allow_multi_family_member == 1) ? 'Yes' : 'No';
        }
    ],
        ['attribute' => 'vendor',
        'value' => function($model) {
            isset($model->societyVendors) ? $vendor = $model->societyVendors->vendor_code : $vendor = 'Other';
            return $vendor;
        },
        'visible' => false, 'filter' => false],
        ['attribute' => 'dpu_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('dpu_type', $searchModel, 'dpu_type'),
        'value' => function ($model) {
            return isset($model->dpu_type) ? Yii::$app->dropdown->getRecords('dpu_type')['data'][$model->dpu_type] : '';
        },],
// Contact Detail
    ['label' => Yii::t('app', 'Society Secretory'), 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->dcs_code, 'society');
            isset($detail->firstname) ? $detail = $detail->firstname . ' ' . $detail->lastname . ' ' . $detail->surname : $detail = '';
            return $detail;
        }
    ],
        ['label' => Yii::t('app', 'Society Secretory Hindi Name'), 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->dcs_code, 'society');
            isset($detail->local_firstname) ? $detail = $detail->local_firstname . ' ' . $detail->local_lastname . ' ' . $detail->local_surname : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Email', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->dcs_code, 'society');
            isset($detail->email) ? $detail = $detail->email : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Department', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->dcs_code, 'society');
            isset($detail->department) ? $detail = $detail->department : $detail = '';
            return $detail;
        }
    ],
// Bank Detail
    ['label' => 'Bank', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->dcs_code, 'society');
            isset($detail->bankCode) ? $detail = $detail->bankCode->bank_name : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Branch', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->dcs_code, 'society');
            isset($detail->branchCode) ? $detail = $detail->branchCode->branch_name : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Bank Account No', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->dcs_code, 'society');
            isset($detail->bank_account_no) ? $detail = $detail->bank_account_no : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'IFSC', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->dcs_code, 'society');
            isset($detail->ifsc) ? $detail = $detail->ifsc : $detail = '';
            return $detail;
        }
    ],
        ['attribute' => 'aadhaar_no'],
        ['attribute' => 'bipl_code', 'label' => Yii::t('app', 'Reference Code'), 'value' => 'societyCodes.bipl_code', 'filter' => false, 'visible' => false],
        ['attribute' => 'is_name_request', 'value' => function($model) {
            return $model->is_name_request == 0 ? 'Downloaded' : 'Not Downloaded';
        }, 'filter' => false, 'visible' => true],
        ['attribute' => 'rate_flag', 'value' => function($model) {
            return $model->rate_flag == 0 ? 'Downloaded' : 'Not Downloaded';
        }, 'filter' => false, 'visible' => true],
        ['attribute' => 'updated_at', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->updated_at, 'php:d-m-Y H:i:s');
        }, 'filter' => false, 'visible' => true],
        [
        'attribute' => 'is_dispatch_mandate', 'filter' => false,
        'value' => function ($model) {
            return isset($model->is_dispatch_mandate) ? Yii::$app->dropdown->getRecords('is_dispatch_mandate')['data'][$model->is_dispatch_mandate] : '';
        }, 'visible' => true
    ],
        [
        'attribute' => 'is_bmc', 'filter' => false,
        'value' => function($model) {
            return ($model->is_bmc == 1) ? 'Yes' : 'No';
        }, 'visible' => FALSE
    ],
        [
        'attribute' => 'credit_sale_allow', 'filter' => false,
        'value' => function($model) {
            return ($model->credit_sale_allow == 1) ? 'Yes' : 'No';
        }, 'visible' => FALSE
    ],
        [
        'attribute' => 'is_active', 'label' => Yii::t('app', 'Status'), 'filter' => false,
        'value' => function($model) {
            return $model->is_active == '1' ? 'Active' : 'In Active';
        },
    ],
        ['label' => 'Bank Verification Remarks', 'visible' => true, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->dcs_code, 'society');
            isset($detail->remarks) ? $detail = $detail->remarks : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Contact Verification Remarks', 'visible' => true, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->dcs_code, 'society');
            isset($detail->remarks) ? $detail = $detail->remarks : $detail = '';
            return $detail;
        }
    ],
        [
        'attribute' => 'is_chiller', 'visible' => true,
        'filter' => Html::activeDropDownList($searchModel, 'is_chiller', [1 => 'Yes', 0 => 'No'], ['class' => 'form-control', 'prompt' => 'Select']),
        'value' => function($model) {
            return ($model->is_chiller == 1) ? 'Yes' : 'No';
        }
    ],
        ['attribute' => 'sap_vendor_code', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'password', 'visible' => false, 'filter' => false],
        ['attribute' => 'antibiotic_check',
        'value' => function($model) {
            return $model->antibiotic_check == 1 ? 'Yes' : 'No';
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'x_col2', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'ts_code_m', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'ts_code_e', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'Channel'), 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->bmcCode, ['channelMaster'], 'channel_desc');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'cutoff', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'lower_milk_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->lowerMilkType, 'animal_type_name');
        }, 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'cutoff_val', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'morning_kms', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'evening_kms', 'filter' => FALSE, 'visible' => FALSE],
        [
        'attribute' => 'status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('provisional_status', $searchModel, 'status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('provisional_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('provisional_status')['data'][$model->status] : '';
        }],
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
        ['attribute' => 'is_security_cheque', 'filter' => FALSE, 'visible' => FALSE,
        'value' => function($model) {
            return ($model->is_security_cheque == 1) ? 'Yes' : 'No';
        }],
        ['attribute' => 'cheque_number', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'cheque_amount', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'cheque_bank', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'security_return_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->security_return_date, 'php:d-m-Y');
        }, 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'security_return_amt', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'security_return_mode', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'is_approved', 'filter' => FALSE, 'visible' => true,
        'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('approved_status', $model, 'is_approved');
        }],
        ['attribute' => 'approved_at', 'filter' => FALSE, 'visible' => FALSE,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->approved_at);
        }],
        ['attribute' => 'approved_by', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'is_bank_verify', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('verified_flag', $model, 'is_bank_verify');
        }, 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'is_aadhar_verify', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('verified_flag', $model, 'is_aadhar_verify');
        }, 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'resp_status', 'filter' => false, 'visible' => true],
        ['attribute' => 'response_msg', 'filter' => false, 'visible' => true],
];
$gridId = 'dcs-list';
$grid_option = [
    'id' => $gridId,
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'views' => function($url, $model) use ($pending_approval) {
            $icon = '<i class="fa fa-eye"></i>';
            $url = ['/organisation/tbl-dcs-provisional/view', 'id' => $model->dcs_provisional_code];
            if ($pending_approval) {
                $icon = '<i class="fa fa-check"></i>';
                $url = ['/organisation/tbl-dcs-provisional/approve-dcs', 'id' => $model->process_approval_code];
            }
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'View'];
            return Html::a($icon, $url, $options);
        },
        'update' => function ($url, $model) use ($pending_approval) {
            $class = '';
            if (!$pending_approval) {
                $class = ($model->is_active === 0 || ($model->status != 'Pending' && $model->status != 'Reroute')) ? 'link-disable' : '';
            }
            $name = $model->dcs_name;
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->dcs_code, 'data-name' => $name];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', $url, $options);
        },
        'add-document' => function ($url, $model) use ($pending_approval) {
            if ($pending_approval) {
                return false;
            }
            $disable = ($model->status == 'Pending' || $model->status == 'Reroute') ? '' : 'disabled';
            $options = ['title' => Yii::t('app', 'Add Document'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-file"></i>', ['/organisation/tbl-dcs-provisional/document-upload', 'id' => $model->dcs_provisional_code], $options);
        },
        'repush' => function ($url, $model) use ($gridId) {
            return Yii::$app->general->createRePushLink($url, $model, $gridId, 'dcs_provisional_code');
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<div id='ImportAttachements'></div>
<?php
$script = "
$(document).ready(function(){
    $(document).on('click','.deact-dcs',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
  bootbox.prompt({
        title: '<div class=\'row\'><div class=\'col-sm-12\'><span>Are you sure you want to deactivate \"'+name+'\"?</span></div></div>', 
        inputType: 'password',
         placeholder: 'Enter your login password',
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
              $('#loader').show();
                 $.ajax({
                        type: 'get',
                        url: '" . Url::to(['deactivate-user']) . "',
                        data:{'id':id,'password':$('.bootbox-form').find('input').val()},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#dcs-list'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record successfully deleted.', timeout: 8000, style: 'successbar'});
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record is not deleted.', timeout: 8000, style: 'errorbar'});
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
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
            url: '" . Url::to(['/organisation/tbl-dcs/import-attachements']) . "',
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
$this->registerJs($script, View::POS_END, 'provisional-dcs-index');
?>