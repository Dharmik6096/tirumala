<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = 'Sub Ledger Detail View';
?>
<div class="panel panel-default panel-grid panel-main tbl-sub-ledger-view hide-grid-settings">
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
                                'attribute' => 'bmc_code',
                                'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'dcs_code',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'dcs_code',
                                'label' => Yii::t('app', 'Ref. Code'),
                                'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'dcs_code',
                                'label' => Yii::t('app', 'Society Name'),
                                'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'sub_ledger_code',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'sub_ledger_name',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'reference_code',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                                [
                                'attribute' => 'type',
                                'value' => !empty(Yii::$app->dropdown->getRecords('consumer_type')['data'][$model->type]) ? Yii::$app->dropdown->getRecords('consumer_type')['data'][$model->type] : '',
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

        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading mt_0">Ledger Sub Ledgers Mapping</h4>
            </div>
            <div class="clearfix"></div>
            <div class="form-grid">
                <?=
                $this->render('../tbl-ledger-sub-ledgers-mapping/_form_grid', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ])
                ?>
            </div>
        </div>

    </div>
</div>