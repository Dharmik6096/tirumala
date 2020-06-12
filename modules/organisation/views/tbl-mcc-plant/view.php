<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::$app->label->title('view', 'MCC');
$this->params['menu'][] = Yii::$app->controls->update($model->mcc_plant_code);
$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-user-circle-o"></i> Contact Details'), ['/organisation/tbl-mcc-plant/contact-details', 'id' => $model->mcc_plant_code], ['class' => 'btn btn-danger btn-block']);
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
                $attributes = [
                    [
                        'columns' => [
                            [
                                'attribute' => 'plant_code',
                                'value' => isset($model->plantCode) ? $model->plantCode->name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'mcc_plant_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'mcc_plant_code_ex',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'ref_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'local_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
//                    'name',
//                    'local_name',
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
//                    [
//                        'columns' => [
//                            [
//                                'attribute' => 'contact_person',
//                                'valueColOptions' => ['style' => 'width:30%'],
//                            ],
//                            [
//                                'attribute' => 'local_contact_person_name',
//                                'valueColOptions' => ['style' => 'width:30%'],
//                            ],
//                        ],
//                    ],
//                    'contact_person',
//                    'local_contact_person_name',
//                    [
//                        'columns' => [
//                            [
//                                'attribute' => 'email',
//                                'valueColOptions' => ['style' => 'width:30%'],
//                            ],
//                            [
//                                'attribute' => 'mobile_no',
//                                'valueColOptions' => ['style' => 'width:30%'],
//                            ],
//                        ],
//                    ],
//                    'email',
//                    'mobile_no',
                    [
                        'columns' => [
                            [
                                'attribute' => 'description',
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
                    [
                        'columns' => [
                            [
                                'attribute' => 'hamlet_code',
                                'value' => isset($model->hamletCode) ? $model->hamletCode->hamlet_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'label' => 'Milk Type',
                                'format' => 'html',
                                'value' => $model->milkType(),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
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
                                'attribute' => 'is_active',
                                'label' => 'Status',
                                'format' => 'html',
                                'value' => GeneralFunctions::getRecordStatus($model->is_active),
                                'valueColOptions' => ['style' => 'width:80%'],
                            ],
                        ],
                    ],
//                    'unionCode.union_name',
//                    'stateCode.state_name',
//                    'districtCode.district_name',
//                    'subDistrictCode.sub_district_name',
//                    'villageCode.village_name',
//                    'hamletCode.hamlet_name',
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

        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">BMC Mapping</h5></div>
        <div class="form-grid">
            <?=
            $this->render('_bmc_grid', [
                'model' => $model,
                'dataProvider' => $bmcdataProvider,
                'searchModel' => $bmcsearchModel,
            ])
            ?>
        </div>
        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Silos Information</h5></div>
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