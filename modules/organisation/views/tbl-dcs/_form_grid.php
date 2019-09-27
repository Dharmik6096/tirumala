<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'visible' => true, 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => true, 'filter' => false],
    ['attribute' => 'route_code', 'label' => Yii::t('app', 'Route Code')],
    ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeMapping, 'route_name');
        }, 'filter' => false],
    ['attribute' => 'dcs_code_ex', 'value' => 'dcs_code_ex', 'visible' => false, 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => 'dcs_code'],
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
        //'filter' => Yii::$app->controls->search_date($searchModel, 'registration_date'),
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->registration_date);
        },],
    [
        'attribute' => 'valid_from',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->valid_from);
        }, 'visible' => false, 'filter' => false],
    // ['attribute' => 'tin_no', 'visible' => false, 'filter' => false],
    // ['attribute' => 'service_tax', 'visible' => false, 'filter' => false],
    ['attribute' => 'dcs_type_code', 'value' => 'dcsTypeCode.dcs_type_name', 'visible' => false, 'filter' => false],
    ['attribute' => 'organisation_type_code', 'value' => 'organisationTypeCode.organisation_type', 'visible' => false, 'filter' => false],
    ['attribute' => 'scheme_type_code', 'value' => 'schemeTypeCode.scheme_type', 'visible' => false, 'filter' => false],
    [
        'attribute' => 'effective_date', 'visible' => false, 'filter' => false,
        //'filter' => Yii::$app->controls->search_date($searchModel, 'effective_date'),
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->effective_date);
        }, 'visible' => false, 'filter' => false],
    ['attribute' => 'pan_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'secretory_info', 'visible' => false, 'filter' => false],
    ['attribute' => 'gst_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'fssi', 'visible' => false, 'filter' => false],
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
        'value' => function($model) {
            return ($model->is_dispatch_mandate == 1) ? 'Yes' : 'No';
        }, 'visible' => FALSE
    ],
];

$grid_option = [
    'id' => 'dcs-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
//        'update' => true,
        'update' => function ($url, $model) {
            $name = $model->dcs_name;
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->dcs_code, 'data-name' => $name];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, $options);
        },
        //'delete' => ['option' => 'dcs_name,dcs_code,tbl-dcs/delete'],
//        'mapping' => function ($url, $model) {
//            $options = ['data-name' => $model->dcs_name, 'data-val' => $model->dcs_code,'data-toggle' => 'tooltip' , 'data-placement' => 'top', 'data-original-title' => 'Village Mapping'];
//            return GhostHtml::a('<i class="fa fa-link"></i>', ['/organisation/tbl-dcs/map-villages', 'id' => $model->dcs_code], $options);
//        },
        /* 'bmc_mapping' => function ($url, $model) {
          $options = ['data-name' => $model->dcs_name, 'data-val' => $model->dcs_code, 'data-toggle' => 'tooltip' , 'data-placement' => 'top', 'data-original-title' => 'Bmc List'];
          $subCenter = $model->getMainSubCenter();
          return GhostHtml::a('<i class="fa fa-plus"></i>', ['/organisation/tbl-dcs-bmc/index', 'dcs' => $model->dcs_code, 'dcsname' => $model->dcs_name, 'subcenter' => isset($subCenter->sub_center_code) ? $subCenter->sub_center_code : 0, 'subname' => isset($subCenter->sub_center_code) ? $subCenter->sub_center_name : '','type'=>'DCS'], $options);
          }, */
        'deactive' => function ($url, $model) {
            $name = $model->dcs_name;
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'deact-dcs ' . $class, 'data-val' => $model->dcs_code, 'data-name' => $name];
            if (Yii::$app->general->checkAccess('/organisation/tbl-dcs/deactivate-user'))
                return GhostHtml::a_alert('<i class="fa fa-close"></i>', ['/organisation/tbl-dcs/deactivate-user'], $options);
            else
                return false;
        },
        'bank-details' => function ($url, $model) {
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-name' => $model->dcs_name, 'data-val' => $model->dcs_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Bank Details', 'class' => '' . $class];
            return GhostHtml::a('<i class="fa fa-university"></i>', ['/organisation/tbl-dcs/bank-details', 'id' => $model->dcs_code], $options);
        },
        'contact-details' => function ($url, $model) {
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-name' => $model->dcs_name, 'data-val' => $model->dcs_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Contact Details', 'class' => '' . $class];
            return GhostHtml::a('<i class="fa fa-user-circle-o"></i>', ['/organisation/tbl-dcs/contact-details', 'id' => $model->dcs_code], $options);
        },
        'dpu-inst' => function ($url, $model) {
            $inst_id = $model->getInstallationId();
            if (!$inst_id) {
                $class = ($model->is_active == 1) ? '' : 'link-disable';
                $url = ['/organisation/tbl-dpu-installation/create', 'id' => $model->dcs_code];
                $icon = '<i class="fa fa-plus"></i>';
            } else {
                $class = '';
                $url = ['/organisation/tbl-dpu-installation/view', 'id' => $inst_id];
                $icon = '<i class="fa fa-upload"></i>';
            }
            $options = ['data-name' => $model->dcs_name, 'data-val' => $model->dcs_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'DPU Installation', 'class' => '' . $class];
            return GhostHtml::a($icon, $url, $options);
        },
        'election-list' => function ($url, $model) {
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-name' => $model->dcs_name, 'data-val' => $model->dcs_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Election Details', 'class' => '' . $class];
            return GhostHtml::a('<i class="fa fa-address-card-o"></i>', ['/organisation/tbl-dcs-election/create', 'dcs_code' => $model->dcs_code], $options);
        },
        'society-status' => function ($url, $model) {

            if (!empty($model->societyStatus)) {
                $status_id = $model->societyStatus->status;
                $name = $model->dcs_name;
                $icon_class = ($status_id == 1) ? 'fa-ban' : 'fa-flask';
                $title = ($status_id == 1) ? 'Stop Collection' : 'Start Collection';
                $class = ($model->is_active == 1) ? '' : 'link-disable';
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => $title, 'class' => 'society-status ' . $class, 'data-val' => $model->dcs_code . ',' . $status_id . ',' . $name, 'data-name' => $title . ' of "' . $name . '"'];
                return GhostHtml::a('<i class="fa ' . $icon_class . '"></i>', ['/organisation/tbl-dcs/society-status', 'dcs_code' => $model->dcs_code, 'coll_status' => $status_id], $options);
            }
        },
        'rate-list' => function ($url, $model) {
            if (!empty($model->tblPurchaseRateApplicabilityUnblock) || !empty($model->tblPurchaseRateApplicabilityBlock)) {
                $icon_class = (!empty($model->tblPurchaseRateApplicabilityBlock)) ? 'fa-bar-chart text-danger' : 'fa-bar-chart text-success';
                $title = (!empty($model->tblPurchaseRateApplicabilityBlock)) ? 'Un-Block Rate Chart' : 'Block Rate Chart';
                $class = ($model->is_active == 1) ? '' : 'link-disable';
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => $title, 'class' => $class];
                return GhostHtml::a('<i class="fa ' . $icon_class . '""></i>', $url, $options);
            }
        },
    /* 'miscellaneous' => function ($url, $model) {
      $options = ['data-name' => $model->dcs_name, 'data-val' => $model->dcs_code, 'data-toggle' => 'tooltip' , 'data-placement' => 'top', 'data-original-title' => 'Miscellaneous List'];
      return GhostHtml::a('<i class="fa fa-thumb-tack"></i>', ['/organisation/tbl-dcs-subcenter-misc/index', 'id' => $model->dcs_code, 'name' => $model->dcs_name, 'type' => 'dcs'], $options);
      } */
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
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
});";
$this->registerJs($script, View::POS_END, 'dcs-index');
