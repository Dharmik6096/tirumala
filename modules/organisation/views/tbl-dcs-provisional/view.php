<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('view', 'Provisional Society');
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="form-grid">
            <div class="table-responsive">
                <?php
                // DetailView Attributes Configuration
                $attributes = [
                    [
                        'columns' => [
                            [
                                'attribute' => 'union_code',
                                'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'bmc_code',
                                'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'label' => Yii::t('app', 'Route Code'),
                                'attribute' => 'route_code',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'route_code',
                                'value' => Yii::$app->general->getforeignkey($model->routeMapping, 'route_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'dcs_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'dcs_code_ex',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'ref_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'dcs_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'dcs_short_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'local_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'local_short_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'phone_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_registered',
                                'value' => ($model->is_registered == 1) ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'registration_code',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'registration_date',
                                'value' => Yii::$app->controls->view_date($model->registration_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'valid_from',
                                'value' => Yii::$app->controls->view_date($model->valid_from),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'dcs_type_code',
                                'value' => Yii::$app->general->getforeignkey($model->dcsTypeCode, 'dcs_type_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'organisation_type_code',
                                'value' => Yii::$app->general->getforeignkey($model->organisationTypeCode, 'organisation_type'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'scheme_type_code',
                                'value' => Yii::$app->general->getforeignkey($model->schemeTypeCode, 'scheme_type'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'effective_date',
                                'value' => Yii::$app->controls->view_date($model->effective_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'secretory_info',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'gst_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'fssi',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'fssi_expiry_date',
                                'value' => Yii::$app->controls->view_date($model->fssi_expiry_date),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'address',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'local_address',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'state_code',
                                'value' => Yii::$app->general->getforeignkey($model->stateCode, 'state_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'district_code',
                                'value' => Yii::$app->general->getforeignkey($model->districtCode, 'district_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'sub_district_code',
                                'value' => Yii::$app->general->getforeignkey($model->subDistrictCode, 'sub_district_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'block_code',
                                'value' => Yii::$app->general->getforeignkey($model->blockCode, 'block_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'village_code',
                                'value' => Yii::$app->general->getforeignkey($model->villageCode, 'village_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'hamlet_code',
                                'value' => Yii::$app->general->getforeignkey($model->hamletCode, 'hamlet_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'pincode',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'allow_multi_family_member',
                                'label' => 'Allow Multi Family Member',
                                'format' => 'html',
                                'value' => $model->allow_multi_family_member == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'vendor',
                                'value' => isset($model->societyVendors) ? $model->societyVendors->vendor_code : Yii::t('app', 'Other'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'dpu_type',
                                'value' => isset($model->dpu_type) ? Yii::$app->dropdown->getRecords('dpu_type')['data'][$model->dpu_type] : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_active',
                                'label' => 'Status',
                                'format' => 'html',
                                'value' => $model->is_active === 0 ? 'In Active' : 'Active',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_dispatch_mandate',
                                'format' => 'html',
                                'value' => isset($model->is_dispatch_mandate) ? Yii::$app->dropdown->getRecords('is_dispatch_mandate')['data'][$model->is_dispatch_mandate] : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'is_bmc',
                                'format' => 'html',
                                'value' => $model->is_bmc == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'credit_sale_allow',
                                'format' => 'html',
                                'value' => $model->credit_sale_allow == 1 ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'default_milk_type',
                                'value' => Yii::$app->general->getStaticDropdownVal('default_milk_type', $model, 'default_milk_type'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'aadhaar_no',
                                'format' => 'html',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'is_chiller',
                                'value' => ($model->is_chiller == 1) ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'sap_vendor_code',
                                'format' => 'html',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'password',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'antibiotic_check',
                                'value' => isset($model->antibiotic_check) ? Yii::$app->dropdown->getRecords('is_type')['data'][$model->antibiotic_check] : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'x_col2',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'ts_code_m',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'ts_code_e',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'bmc_code',
                                'label' => Yii::t('app', 'Channel'),
                                'value' => Yii::$app->general->getmultiforeignkey($model->bmcCode, ['channelMaster'], 'channel_desc'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'lower_milk_type',
                                'value' => Yii::$app->general->getforeignkey($model->lowerMilkType, 'animal_type_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'cutoff',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'cutoff_val',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'morning_kms',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'evening_kms',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'bank_code',
                                'value' => !empty($model->bankCode) ? $model->bankCode->bank_name : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'branch_code',
                                'value' => !empty($model->branchCode) ? $model->branchCode->branch_name : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'bank_account_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'ifsc',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'pan_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'is_security_cheque',
                                'value' => ($model->is_security_cheque == 1) ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'cheque_number',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'cheque_amount',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'cheque_bank',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'security_return_date',
                                'value' => Yii::$app->controls->view_date($model->security_return_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'security_return_amt',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'security_return_mode',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_approved',
                                'format' => 'html',
                                'value' => Yii::$app->general->getStaticDropdownVal('approved_status', $model, 'is_approved'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'provisional_from',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_aadhar_verify',
                                'value' => Yii::$app->general->getStaticDropdownVal('verified_flag', $model, 'is_aadhar_verify'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'is_bank_verify',
                                'value' => Yii::$app->general->getStaticDropdownVal('verified_flag', $model, 'is_bank_verify'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                ];

// View file rendering the widget
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => $attributes,
                    'mode' => 'view',
                    'bordered' => true,
                    'striped' => false,
                    'responsive' => true,
                    'hAlign' => 'left',
                    'vAlign' => 'top',
                    'deleteOptions' => [// your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading">Document Upload</h4>
                </div>
                <div class="col-sm-12">
                    <?php
                    $attribute = [
                            ['attribute' => 'doc_id', 'value' => function($model) {
                                return Yii::$app->general->getforeignkey($model->docId, 'doc_name');
                            }],
                    ];
                    $grid_option = [
                        'id' => 'document',
                        'attributes' => $attribute,
                        'active_column' => false,
                        'actions' => [
                            'view-attachment' => function ($url, $model) {
                                $attachemnt = $model->attachment;
                                $url = !empty($attachemnt) ? $attachemnt : '';
                                return Html::a('<i class="fa fa-eye"></i>', $url, ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'View', 'target' => '_blank']);
                            },
                        ]
                    ];
                    Yii::$app->grid->bind($dataProviderOther, $attachment, $grid_option, '', false);
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading">Previous Approval Detail</h4>
                </div>
                <div class="col-sm-12">
                    <?=
                    $this->render('_process_approval_grid', [
                        'processApprovalModel' => $processApprovalModel,
                        'processApprovalDataProvider' => $processApprovalDataProvider,
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>