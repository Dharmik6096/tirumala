<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>
<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => FALSE],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => FALSE],
    ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'filter' => FALSE],
    ['attribute' => 'customer_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        }, 'filter' => Yii::$app->dropdown->dropdownfilter('customer_type', $searchModel, 'customer_type', Yii::t('app', 'Select'))],
    ['attribute' => 'customer_code'],
    ['attribute' => 'customer_code_ex'],
    ['attribute' => 'ref_code'],
    ['attribute' => 'customer_name'],
    ['attribute' => 'local_name', 'filter' => FALSE],
    ['attribute' => 'gst_no',],
    ['attribute' => 'address', 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'local_address', 'filter' => FALSE, 'visible' => FALSE],
    ['label' => Yii::t('app', 'Contact Person'), 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->customer_code, 'customer');
            isset($detail->firstname) ? $detail = $detail->firstname . ' ' . $detail->lastname . ' ' . $detail->surname : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Email', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->customer_code, 'customer');
            isset($detail->email) ? $detail = $detail->email : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Department', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->customer_code, 'customer');
            isset($detail->department) ? $detail = $detail->department : $detail = '';
            return $detail;
        }
    ],
// Bank Detail
    ['label' => 'Bank', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->customer_code, 'customer');
            isset($detail->bankCode) ? $detail = $detail->bankCode->bank_name : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Branch', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->customer_code, 'customer');
            isset($detail->branchCode) ? $detail = $detail->branchCode->branch_name : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'Bank Account No', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->customer_code, 'customer');
            isset($detail->bank_account_no) ? $detail = $detail->bank_account_no : $detail = '';
            return $detail;
        }
    ],
    ['label' => 'IFSC', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->customer_code, 'customer');
            isset($detail->ifsc) ? $detail = $detail->ifsc : $detail = '';
            return $detail;
        }
    ],
    ['attribute' => 'aadhaar_no'],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Bank Verification'), 'value' => function($model) {
            $flag = Yii::$app->general->getforeignkey($model->mainBankDetails, 'is_verified');
            return $flag == 1 ? 'Verified' : ($flag == 2 ? 'Reject' : 'Pending');
        }, 'filter' => false],
    ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Contact Verification'), 'value' => function($model) {
            $flag = Yii::$app->general->getforeignkey($model->mainContactDetails, 'is_contact_verified');
            return $flag == 1 ? 'Verified' : ($flag == 2 ? 'Reject' : 'Pending');
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'customer-master-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
        'bank-details' => function ($url, $model) {
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-name' => $model->customer_name, 'data-val' => $model->customer_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Bank Details', 'class' => '' . $class];
            return GhostHtml::a('<i class="fa fa-university"></i>', ['/organisation/tbl-customer-master/bank-details', 'id' => $model->customer_code], $options);
        },
        'contact-details' => function ($url, $model) {
            $class = ($model->is_active == 1) ? '' : 'link-disable';
            $options = ['data-name' => $model->customer_name, 'data-val' => $model->customer_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Contact Details', 'class' => '' . $class];
            return GhostHtml::a('<i class="fa fa-user-circle-o"></i>', ['/organisation/tbl-customer-master/contact-details', 'id' => $model->customer_code], $options);
        },
        'upload-photos' => function ($url, $model) {
            $id = $model->customer_code;
            $type = 'CUSTOMER';
            $class = Yii::$app->general->getforeignkey($model->mainBankDetails, 'is_verified') == 1 ? 'link-disable disabled' : '';
            $url = ['/organisation/tbl-customer-master/import-attachements', 'id' => $id];
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Upload', 'class' => 'upload-photo' . $class, 'data-val' => $id, 'data-name' => $type];
            return GhostHtml::a_alert('<i class="fa fa-cloud-upload"></i>', $url, $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<div id="ImportAttachements"></div>
<?php
$script = "
$(document).ready(function(){
    $(document).on('click','.upload-photo',function(e){
        $('#pageloader').show();
        $('#loadercontent').show();
        var code= $(this).attr('data-val');
        var type= $(this).attr('data-name');
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/organisation/tbl-customer-master/import-attachements']) . "',
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
$this->registerJs($script, View::POS_END, 'customer-grid-index');
