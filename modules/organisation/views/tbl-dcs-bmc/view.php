<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = 'BMC Detail View';
$this->params['menu'][] = Yii::$app->controls->update($model->bmc_code);
$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-user-circle-o"></i> Contact Details'), ['/organisation/tbl-dcs-bmc/contact-details', 'id' => $model->bmc_code], ['class' => 'btn btn-danger btn-block']);
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
                                'attribute' => 'mcc_plant_code',
                                'value' => isset($model->tblMccPlant) ? $model->tblMccPlant->name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'bmc_code',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'bmc_code_ex',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'ref_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ], [
                        'columns' => [
                            [
                                'attribute' => 'bmc_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'local_name',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
//                    'bmc_name',
//                    'bmc_type_code',
                    [
                        'columns' => [
                            [
                                'attribute' => 'bmc_type_code',
                                'value' => isset($model->bmc_type_code) ? $model->tblBmcType->bmc_type_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'union_code',
                                'value' => isset($model->unionCode) ? $model->unionCode->union_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
//                    'description
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
                                'attribute' => 'village_code',
                                'value' => isset($model->villageCode) ? $model->villageCode->village_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
//                    'unionCode.union_name',
//                    'stateCode.state_name',
//                    'districtCode.district_name',
//                    'subDistrictCode.sub_district_name',
//                    'villageCode.village_name',
//                    'hamletCode.hamlet_name',
                    [
                        'columns' => [
                            [
                                'attribute' => 'hamlet_code',
                                'value' => isset($model->hamletCode) ? $model->hamletCode->hamlet_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'model',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
//                    'model',
                    [
                        'columns' => [
                            [
                                'attribute' => 'capacity',
                                'format' => 'html',
                                'value' => isset($model->capacity0) ? $model->capacity0->value : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'valid_from',
                                'value' => Yii::$app->controls->view_date($model->valid_from),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
//                    'manufacturerCode.manufacturer_name',
                    [
                        'columns' => [
                            [
                                'attribute' => 'manufacturer_code',
                                'value' => isset($model->manufacturerCode) ? $model->manufacturerCode->manufacturer_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'label' => 'Milk Type',
                                'format' => 'html',
                                'value' => $model->milkType(),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
//                            [
//                                'attribute' => 'bmc_milk_type',
//                                'value' => isset($model->bmcMilkType) ? $model->bmcMilkType->animal_type_name : '',
//                                'valueColOptions' => ['style' => 'width:30%'],
//                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_weight_manual',
                                'value' => isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual] : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'is_quality_manual',
                                'value' => isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual] : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'rate_calculate_on_merge',
                                'value' => isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->rate_calculate_on_merge]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->rate_calculate_on_merge] : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'is_active',
                                'label' => 'Status',
                                'format' => 'html',
                                'value' => GeneralFunctions::getRecordStatus($model->is_active),
                                'valueColOptions' => ['style' => 'width:30%'],
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
        <!-- <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Society Mapping</h5></div> -->
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Society Mapping</h4>
            </div>
            <div class="form-grid">
                <?=
                $this->render('_society_grid', [
                    'model' => $model,
                    'dataProvider' => $sdataProvider,
                    'searchModel' => $ssearchModel,
                ])
                ?>
            </div>
        </div>
        <!-- <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Silos Information</h5></div> -->
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading">Silos Information</h4>
            </div>
            <div class="form-grid">
                <?=
                $this->render('@app/modules/organisation/views/tbl-bmc-silos-info/_form_grid', [
                    'model' => $model,
                    'dataProvider' => $sndataProvider,
                    'searchModel' => $snsearchModel,
                    'isaction' => $isaction
                ])
                ?>
            </div>
        </div>

    </div>
</div>