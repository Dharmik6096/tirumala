<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblPurchaseRate */

$this->title = Yii::$app->label->title('view', 'Rate Recalculation');
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
                                'attribute' => 'mcc_plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
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
                                'attribute' => 'rate_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'rate_code',
                                'label' => Yii::t('app', 'Rate Desc.'),
                                'value' => !empty(strtolower($model->rate_type) == 'member' ? Yii::$app->general->getforeignkey($model->rateDescCode, 'description') : Yii::$app->general->getforeignkey($model->dcsRateDescCode, 'description')) ?
                                (strtolower($model->rate_type) == 'member' ? Yii::$app->general->getforeignkey($model->rateDescCode, 'reference_code') : '') . '(' . (strtolower($model->rate_type) == 'member' ? Yii::$app->general->getforeignkey($model->rateDescCode, 'description') : Yii::$app->general->getforeignkey($model->dcsRateDescCode, 'description')) . ')' : (strtolower($model->rate_type) == 'member' ? Yii::$app->general->getforeignkey($model->rateDescCode, 'reference_code') : ''),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ]
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'from_date',
                                'value' => Yii::$app->controls->view_date($model->from_date),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'to_date',
                                'value' => Yii::$app->controls->view_date($model->to_date),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ]
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'from_shift',
                                'value' => isset($model->fromShiftId) ? $model->fromShiftId->shift : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'to_shift',
                                'value' => isset($model->toShiftId) ? $model->toShiftId->shift : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'recalc_type',
                                'valueColOptions' => ['style' => 'width:100%']
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
        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"><?= Yii::t('app', 'Rate Recalculation Detail') ?></h5></div>
        <div class="form-grid">
            <?php echo $this->render('@app/modules/dcsoperation/views/tbl-rate-recalculation/_form_grid_view', ['dataProviderGrid' => $dataProviderGrid, 'searchModelGrid' => $searchModelGrid]); ?>
        </div>
    </div>
</div>



