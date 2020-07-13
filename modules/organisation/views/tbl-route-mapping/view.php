<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::$app->label->title('view', 'Route Mapping');
$this->params['menu'][] = Yii::$app->controls->update($model->route_code);
//$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-university"></i> Bank Details'), ['/organisation/tbl-route-mapping/bank-details', 'id' => $model->route_code], ['class' => 'btn btn-danger btn-block']);
$this->params['menu'][] = GhostHtml::a(Yii::t('app', '<i class="fa fa-user-circle-o"></i> Contact Details'), ['/organisation/tbl-route-mapping/contact-details', 'id' => $model->route_code], ['class' => 'btn btn-danger btn-block']);
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
                                'attribute' => 'route_code',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'route_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'route_code_ex',
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
                                'attribute' => 'local_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'route_type',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
//                    'route_code',
//                    'route_name',
//                    'local_name',
//                    'route_type',
                    [
                        'columns' => [
                            /* [
                              'attribute' => 'from_dest',
                              'value' => $model->getDestinationName($model->from_type, $model->from_dest),
                              'valueColOptions' => ['style' => 'width:30%'],
                              ], */
                            [
                                'attribute' => 'to_dest',
                                'value' => $model->getDestinationName($model->to_type, $model->to_dest),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'to_type',
                                'value' => $model->to_type,
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'capacity',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'valid_from',
                                'value' => Yii::$app->controls->view_date($model->valid_from),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
//                    'capacity',
                    [
                        'columns' => [
                            [
                                'attribute' => 'morning_start_time',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'morning_end_time',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'evening_start_time',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'evening_end_time',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
//                    'morning_start_time',
//                    'morning_end_time',
//                    'evening_start_time',
//                    'evening_end_time',
                    [
                        'columns' => [
                            [
                                'attribute' => 'route_length_kms',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'union_code',
                                'value' => isset($model->unionCode) ? $model->unionCode->union_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
//                    'route_length_kms',
                    [
                        'columns' => [
                            [
                                'attribute' => 'vehicle_type_code',
                                'value' => isset($model->vehicleType) ? $model->vehicleType->vehicle_type_name : '',
                                'valueColOptions' => ['style' => 'width:30%'],
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
                    'deleteOptions' => [ // your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>

                <!--                <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">Bank Details</h5></div>
                                <div class="form-grid">
                                    <? =
                                    $this->render('../../../details/views/tbl-bank-details/_bank_details', [
                                        'model' => $model,
                                        'dataProvider' => $bdataProvider,
                                        'searchModel' => $bsearchModel,
                                    ])
                                    ?>
                                </div>-->

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
    </div>
</div>