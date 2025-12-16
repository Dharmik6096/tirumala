<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Manual Collection Request');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default panel-grid panel-main hide-grid-settings">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model, 'index', null, true); ?>
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
                                'attribute' => 'dcs_code',
                                'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'totalRequestCount',
                                'label' => Yii::t('app', 'Total Request Count'),
                                'value' => $dataProvider->getTotalCount(),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'totalComplainCount',
                                'label' => Yii::t('app', 'Total Complain Count'),
                                'value' => $totalComplainCount,
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                ];

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

        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading mt_0"><?= Yii::t('app', 'Last 2 month Request Details') ?></h4>
            </div>
            <div class="clearfix"></div>
            <div class="form-grid">
                <?=
                $this->render('_complaint_activity', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $manualCollectionSearchModel,
                ])
                ?>
            </div>
        </div>
    </div>
</div>