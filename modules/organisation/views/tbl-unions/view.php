<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use app\modules\usermanagement\components\GhostHtml;

$this->title = Yii::$app->label->title('view', 'union');
$this->params['menu'][] = Yii::$app->controls->update($model->union_code);
$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-university"></i> Bank Details'), ['/organisation/tbl-unions/bank-details', 'id' => $model->union_code], ['class' => 'btn btn-danger btn-block']);
$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fas fa-user-circle"></i> Contact Details'), ['/organisation/tbl-unions/contact-details', 'id' => $model->union_code], ['class' => 'btn btn-danger btn-block']);
$this->params['menu'][] = GhostHtml::a('<i class="fa fa fa-cog"></i>' . Yii::t('app', 'Union Configurations'), ['/configuration/tbl-config/create', 'id' => $model->union_code], ['class' => 'btn btn-danger btn-block']);
$this->params['menu'][] = GhostHtml::a('<i class="fa fa fa-cog"></i>' . Yii::t('app', 'Control Mapping'), ['/configuration/tbl-config-mapping/index', 'id' => $model->union_code], ['class' => 'btn btn-danger btn-block']);
$this->params['menu'][] = GhostHtml::a('<i class="fa fa fa fa-money-bill"></i>' . Yii::t('app', 'Payment Configurations'), ['/configuration/tbl-config-mapping/index', 'id' => $model->union_code], ['class' => 'btn btn-danger btn-block']);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?php if (Yii::$app->general->checkAccess('/organisation/tbl-unions/index')) { ?>
            <?= Yii::$app->controls->cancel($model); ?>
        <?php } ?>
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
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'logo',
                                'format' => 'raw',
                                'value' => Html::img($model->logo, ['class' => 'img-responsive']),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'union_code_ex',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'union_name',
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
                                'attribute' => 'union_short_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'districts',
                                'label' => 'Mapped Districts',
                                'valueColOptions' => ['style' => 'width:80%'],
                                'format' => 'html',
                                'value' => $model->getDistrictList()
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'registration_no',
                                'valueColOptions' => ['style' => 'width:30%']
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
                                'attribute' => 'state_code',
                                'value' => isset($model->stateCode) ? $model->stateCode->state_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'district_code',
                                'value' => isset($model->districtCode) ? $model->districtCode->district_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'sub_district_code',
                                'value' => isset($model->subDistrictCode) ? $model->subDistrictCode->sub_district_name : '',
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
                    [
                        'columns' => [
                            [
                                'attribute' => 'city',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'address',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'local_address',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'pincode',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'phone_no',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'fax_no',
                                'valueColOptions' => ['style' => 'width:80%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'contact_person_pan_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'upi_no',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'gst_no',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'is_active',
                                'label' => 'Status',
                                'format' => 'html',
                                'value' => GeneralFunctions::getRecordStatus($model->is_active),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ]
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
    </div>
</div>