<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblPurchaseRate */

$this->title = Yii::$app->label->title('view', 'Rate Recalculation');
//$this->title = $model->purchase_rate_code;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Purchase Rates'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
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
                    /*[
                        'columns' => [
                            [
                                'attribute' => 'rate_recalculation_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'rate_type',
                                'valueColOptions' => ['style' => 'width:80%']
                            ],
                        ]
                    ],*/
                    [
                        'columns' => [
                            [
                                'attribute' => 'rate_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'purchase_rate_code',
                                'value'=> $model->rate_type == 'DCS' ? Yii::$app->general->getforeignkey($model->rateDescCode, 'description') : Yii::$app->general->getforeignkey($model->dcsRateDescCode, 'description'),
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
                    [
                        'columns' => [
                           [
                                'attribute' => 'dcs_code',
                                //'value' => 'dcs_code',
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
                    'deleteOptions' => [ // your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>



