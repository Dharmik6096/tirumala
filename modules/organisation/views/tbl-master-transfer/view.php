<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Transfer Requests');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <?php
            $attributes = [
                [
                    'columns' => [
                        [
                            'attribute' => 'master_transfer_code',
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'master_type',
                            'value' => Yii::$app->general->getforeignkey($model->requestType, 'master_type_text'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'transfer_type',
                            'value' => Yii::$app->general->getforeignkey($model->requestType, 'transfer_type_text'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'union_code',
                            'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'old_member_code',
                            'label' => Yii::t('app', 'Member Code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'old_member_code',
                            'label' => Yii::t('app', 'Vendor Code'),
                            'value' => Yii::$app->general->getforeignkey($model->oldMemberCode, 'vendor_code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [

                        [
                            'attribute' => 'old_member_code',
                            'value' => Yii::$app->general->getforeignkey($model->oldMemberCode, 'member_name'),
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'old_dcs_code',
                            'label' => Yii::t('app', 'DCS Code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'old_dcs_code',
                            'value' => Yii::$app->general->getforeignkey($model->oldDcsCode, 'dcs_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'new_dcs_code',
                            'label' => Yii::t('app', 'New DSK Code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'new_dcs_code',
                            'label' => Yii::t('app', 'New DSK'),
                            'value' => Yii::$app->general->getforeignkey($model->newDcsCode, 'dcs_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
//                [
//                    'columns' => [
//                        [
//                            'attribute' => 'old_bmc_code',
//                            'value' => Yii::$app->general->getforeignkey($model->oldBmcCode, 'bmc_name'),
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
//                        [
//                            'attribute' => 'new_bmc_code',
//                            'label' => Yii::t('app', 'New BMC'),
//                            'value' => Yii::$app->general->getforeignkey($model->newBmcCode, 'bmc_name'),
//                            'valueColOptions' => ['style' => 'width:30%']
//                        ],
//                    ],
//                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'old_mcc_plant_code',
                            'label' => Yii::t('app', 'MCC Code'),
                            'value' => Yii::$app->general->getforeignkey($model->oldMccPlantCode, 'sloc_code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'old_mcc_plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->oldMccPlantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'new_mcc_plant_code',
                            'label' => Yii::t('app', 'New MCC Code'),
                            'value' => Yii::$app->general->getforeignkey($model->newMccPlantCode, 'sloc_code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'new_mcc_plant_code',
                            'label' => Yii::t('app', 'New MCC'),
                            'value' => Yii::$app->general->getforeignkey($model->newMccPlantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'old_route_code',
                            'label' => Yii::t('app', 'Route Code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'old_route_code',
                            'value' => Yii::$app->general->getforeignkey($model->oldRouteCode, 'route_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'new_route_code',
                            'label' => Yii::t('app', 'New Route Code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'new_route_code',
                            'label' => Yii::t('app', 'New Route'),
                            'value' => Yii::$app->general->getforeignkey($model->newRouteCode, 'route_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'wef_date',
                            'value' => Yii::$app->controls->view_date($model->wef_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                        [
                            'attribute' => 'status',
                            'value' => isset(Yii::$app->dropdown->getRecords('sap_file_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('sap_file_status')['data'][$model->status] : '',
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
                'deleteOptions' => [ // your ajax delete parameters
                    'params' => ['id' => 1000, 'kvdelete' => true],
                ],
                'container' => ['id' => 'kv-demo'],
            ]);
            ?>
        </div>
    </div>
</div>
