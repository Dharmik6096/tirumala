<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::$app->label->title('view', 'Society');
$this->params['menu'][] = Yii::$app->controls->update($model->dcs_code);

$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-university"></i> Bank Details'), ['/organisation/tbl-dcs/bank-details', 'id' => $model->dcs_code], ['class' => 'btn btn-danger btn-block']);
$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-user-circle-o"></i> Contact Details'), ['/organisation/tbl-dcs/contact-details', 'id' => $model->dcs_code], ['class' => 'btn btn-danger btn-block']);
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
                            ['label' => Yii::t('app', 'Route Code'),
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
//                    [
//                        'columns' => [
//                            [
//                                'attribute' => 'is_bmc',
//                                'value' => $model->isBmcValue(),
//                                'valueColOptions' => ['style' => 'width:80%']
//                            ],
////                            [
////                                'attribute' => 'destination_code',
////                                'value' => isset($model->tblDcsBmc) ? $model->tblDcsBmc->bmc_name : '',
////                                'valueColOptions' => ['style' => 'width:30%']
////                            ],
//                        ],
//                    ],
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
                    /* [
                      'columns' => [
                      [
                      'attribute' => 'villages',
                      'label' => 'Mapped Villages',
                      'format' => 'html',
                      'value' => $model->getVillageList(),
                      'valueColOptions' => ['style' => 'width:80%'],
                      ],
                      ],
                      ], */
                    [
                        'columns' => [
                            [
                                'label' => 'Milk Type',
                                'format' => 'html',
                                'value' => $model->milkType(),
                                'valueColOptions' => ['style' => 'width:80%'],
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
                                'label' => 'Pours To BMC',
                                'value' => Yii::$app->general->getmultiforeignkey($model->societyCodes, ['bmcCode'], 'bmc_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'pan_no',
                                'valueColOptions' => ['style' => 'width:30%'],
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
                    /* [
                      'group' => true,
                      'label' => 'Contact Details',
                      'rowOptions' => ['class' => 'bg-default']
                      ],
                      [
                      'columns' => [
                      [
                      'attribute' => 'contact_person',
                      'valueColOptions' => ['style' => 'width:30%']
                      ],
                      [
                      'attribute' => 'local_contact_person',
                      'valueColOptions' => ['style' => 'width:30%']
                      ],
                      ],
                      ],
                      [
                      'columns' => [
                      [
                      'attribute' => 'mobile_no',
                      'valueColOptions' => ['style' => 'width:30%'],
                      ],
                      [
                      'attribute' => 'email',
                      'valueColOptions' => ['style' => 'width:30%']
                      ],
                      ],
                      ],
                      [
                      'group' => true,
                      'label' => 'Bank Details',
                      'rowOptions' => ['class' => 'bg-default']
                      ],
                      [
                      'columns' => [
                      [
                      'attribute' => 'bank_code',
                      'value' => $model->bankCode->bank_name,
                      'valueColOptions' => ['style' => 'width:30%'],
                      ],
                      [
                      'attribute' => 'branch_code',
                      'value' => $model->branchCode->branch_name,
                      'valueColOptions' => ['style' => 'width:30%'],
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
                      'valueColOptions' => ['style' => 'width:30%'],
                      ],
                      ],
                      ], */
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
//                            [
//                                'attribute' => 'upi_no',
//                                'valueColOptions' => ['style' => 'width:30%'],
//                            ],
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
                                'attribute' => 'bipl_code',
                                'value' => Yii::$app->general->getforeignkey($model->societyCodes, 'bipl_code'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'vendor',
                                'value' => isset($model->societyVendors) ? $model->societyVendors->vendor_code : Yii::t('app', 'Other'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'dpu_type',
                                'value' => isset($model->dpu_type) ? Yii::$app->dropdown->getRecords('dpu_type')['data'][$model->dpu_type] : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'is_active',
                                'label' => 'Status',
                                'format' => 'html',
                                'value' => $model->is_active == '1' ? (Yii::$app->general->getforeignkey($model->activeStatus, 'is_active') === 0 ? 'In Active' : 'Active') : 'In Active',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
//                            [
//                                'label' => 'IMEI Number',
//                                'value' => isset($model->societyCodes) ? $model->societyCodes->imei_no : '',
//                                'valueColOptions' => ['style' => 'width:30%']
//                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'bipl_code',
                                'label' => Yii::t('app', 'Reference Code'),
                                'value' => Yii::$app->general->getforeignkey($model->societyCodes, 'bipl_code'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'is_dispatch_mandate',
                                'format' => 'html',
                                'value' => isset($model->is_dispatch_mandate) ? Yii::$app->dropdown->getRecords('is_dispatch_mandate')['data'][$model->is_dispatch_mandate] : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
//                            [
//                                'attribute' => 'is_weight_manual',
//                                'format' => 'html',
//                                'value' => $model->is_weight_manual == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
//                                'valueColOptions' => ['style' => 'width:30%'],
//                            ],
                        ],
                    ],
//                    [
//                        'columns' => [
//                            [
//                                'attribute' => 'is_quality_manual',
//                                'format' => 'html',
//                                'value' => $model->is_quality_manual == 1 ? 'Yes' : 'No',
//                                'valueColOptions' => ['style' => 'width:30%'],
//                            ],
//                        ],
//                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_bmc',
                                'format' => 'html',
                                'value' => $model->is_bmc == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'credit_sale_allow',
                                'format' => 'html',
                                'value' => $model->credit_sale_allow == 1 ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'default_milk_type',
                                'value' => Yii::$app->general->getStaticDropdownVal('default_milk_type', $model, 'default_milk_type'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'aadhaar_no',
                                'format' => 'html',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_chiller',
                                'value' => ($model->is_chiller == 1) ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'sap_vendor_code',
                                'format' => 'html',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'password',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'antibiotic_check',
                                'value' => isset($model->antibiotic_check) ? Yii::$app->dropdown->getRecords('is_type')['data'][$model->antibiotic_check] : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'x_col2',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'ts_code_m',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'ts_code_e',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'bmc_code',
                                'label' => Yii::t('app', 'Channel'),
                                'value' => Yii::$app->general->getmultiforeignkey($model->bmcCode, ['channelMaster'], 'channel_desc'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'lower_milk_type',
                                'value' => Yii::$app->general->getforeignkey($model->lowerMilkType, 'animal_type_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'cutoff',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'cutoff_val',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'morning_kms',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'evening_kms',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'machine_owned',
                                'value' => isset($model->machine_owned) ? Yii::$app->dropdown->getRecords('machine_owned_type')['data'][$model->machine_owned] : '',
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
                                'attribute' => 'is_security_cheque',
                                'value' => ($model->is_security_cheque == 1) ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'type_of_dcs',
                                'value' => isset($model->type_of_dcs) ? Yii::$app->dropdown->getRecords('type_of_dcs')['data'][$model->type_of_dcs] : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'sim_network',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'sim_no',
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

        <!-- <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Bank Details</h5></div> -->
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Bank Details</h4>
            </div>
            <div class="form-grid">
                <?=
                $this->render('../../../details/views/tbl-bank-details/_bank_details', [
                    'model' => $model,
                    'dataProvider' => $bdataProvider,
                    'searchModel' => $bsearchModel,
                ])
                ?>
            </div>
        </div>
        <!-- <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Contact Details</h5></div> -->
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Contact Details</h4>
            </div>
            <div class="form-grid">
                <?=
                $this->render('../../../details/views/tbl-contact-details/_contact_details', [
                    'model' => $model,
                    'dataProvider' => $cdataProvider,
                    'searchModel' => $csearchModel,
                ])
                ?>
            </div>       
        </div> 
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'DCS') . ' Deactivate Range' ?></h4>
            </div>
            <div class="form-grid">
                <?=
                $this->render('_dcs_deactivate', [
                    'model' => $model,
                    'dataProvider' => $ddataProvider,
                    'searchModel' => $dsearchModel,
                ])
                ?>
            </div>       
        </div> 
    </div>
</div>