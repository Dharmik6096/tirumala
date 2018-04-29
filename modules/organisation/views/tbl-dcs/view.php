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
                                'value' => isset($model->unionCode) ? $model->unionCode->union_name : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'dcs_code_ex',
                                'valueColOptions' => ['style' => 'width:30%']
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
                                'label' => 'Milk Type',
                                'format' => 'html',
                                'value' => $model->milkType(),
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
                                'attribute' => 'dcs_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'dcs_short_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'local_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'local_short_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'phone_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'is_registered',
                                'value' => ($model->is_registered == 1) ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'registration_code',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'registration_date',
                                'value' => Yii::$app->controls->view_date($model->registration_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'valid_from',
                                'value' => Yii::$app->controls->view_date($model->valid_from),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'dcs_type_code',
                                'value' => isset($model->dcsTypeCode) ? $model->dcsTypeCode->dcs_type_name : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'organisation_type_code',
                                'value' => isset($model->organisationTypeCode) ? $model->organisationTypeCode->organisation_type : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'scheme_type_code',
                                'value' => isset($model->schemeTypeCode) ? $model->schemeTypeCode->scheme_type : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'effective_date',
                                'value' => Yii::$app->controls->view_date($model->effective_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'label' => 'Pours To BMC',
                                'value' => isset($model->societyCodes->bmcCode) ? $model->societyCodes->bmcCode->bmc_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'pan_no',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'secretory_info',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'gst_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'fssi',
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
                                'value' => isset($model->stateCode) ? $model->stateCode->state_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'district_code',
                                'value' => isset($model->districtCode) ? $model->districtCode->district_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'sub_district_code',
                                'value' => isset($model->subDistrictCode) ? $model->subDistrictCode->sub_district_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'block_code',
                                'value' => isset($model->blockCode) ? $model->blockCode->block_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'village_code',
                                'value' => isset($model->villageCode) ? $model->villageCode->village_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'hamlet_code',
                                'value' => isset($model->hamletCode) ? $model->hamletCode->hamlet_name : '',
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
                                'attribute' => 'pincode',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
//                            [
//                                'attribute' => 'upi_no',
//                                'valueColOptions' => ['style' => 'width:30%'],
//                            ],
                            [
                                'attribute' => 'allow_multi_family_member',
                                'label' => 'Allow Multi Family Member',
                                'format' => 'html',
                                'value' => $model->allow_multi_family_member == 1 ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:80%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'bipl_code',
                                'value' => $model->societyCodes->bipl_code,
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'vendor',
                                'value' => isset($model->societyVendors) ? $model->societyVendors->vendor_code : 'Other',
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
                                'value' => GeneralFunctions::getRecordStatus($model->is_active),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'bipl_code',
                                'label'=>Yii::t('app', 'Reference Code'),
                                'value' => $model->societyCodes->bipl_code,
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
//                            [
//                                'label' => 'IMEI Number',
//                                'value' => isset($model->societyCodes) ? $model->societyCodes->imei_no : '',
//                                'valueColOptions' => ['style' => 'width:30%']
//                            ],
//                            [
//                                'attribute' => 'is_active',
//                                'label' => 'Status',
//                                'format' => 'html',
//                                'value' => GeneralFunctions::getRecordStatus($model->is_active),
//                                'valueColOptions' => ['style' => 'width:80%'],
//                            ],
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
                    'deleteOptions' => [ // your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>

        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Bank Details</h5></div>
        <div class="form-grid">
            <?=
            $this->render('../../../details/views/tbl-bank-details/_bank_details', [
                'model' => $model,
                'dataProvider' => $bdataProvider,
                'searchModel' => $bsearchModel,
            ])
            ?>
        </div>

        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Contact Details</h5></div>
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
</div>