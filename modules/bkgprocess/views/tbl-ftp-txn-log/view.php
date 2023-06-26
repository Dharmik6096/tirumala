<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use app\modules\usermanagement\components\GhostHtml;

$this->title = Yii::$app->label->title('view', 'File');
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
                                'label' => Yii::t('app', 'Ref. Code'),
                                'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'module_code',
                                'label' => Yii::t('app', 'DCS'),
                                'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'total_count',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'success_count',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'error_count',
                                'valueColOptions' => ['style' => 'width:80%']
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

        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle">File Details</h5></div>
        <div class="form-grid">
            <?php
            $attribute = [
                ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'filter' => false],
                ['label' => Yii::t('app', 'DCS'), 'attribute' => 'dcs_code', 'value' => function($searchModel) {
                        return Yii::$app->general->getforeignkey($searchModel->dcsCode, 'dcs_name');
                    }, 'filter' => false],
                ['attribute' => 'cp_code', 'filter' => false],
                ['attribute' => 'date', 'label' => Yii::t('app', 'Date'), 'value' => function($model) {
                        return Yii::$app->controls->view_date($model->date);
                    }, 'filter' => false],
                ['attribute' => 'time', 'label' => Yii::t('app', 'time'), 'filter' => false],
                ['attribute' => 'milk_type', 'filter' => false],
                ['attribute' => 'local_code', 'filter' => false],
                ['attribute' => 'quantity', 'filter' => false],
                ['attribute' => 'fat', 'filter' => false],
                ['attribute' => 'snf', 'filter' => false],
                ['attribute' => 'awm', 'filter' => false],
                ['attribute' => 'amount', 'filter' => false],
                ['attribute' => 'quantity_mode', 'filter' => false],
                ['attribute' => 'measurement_mode', 'filter' => false],
                ['attribute' => 'shift', 'filter' => false],
                ['attribute' => 'rate', 'filter' => false],
                ['attribute' => 'process_type', 'filter' => false],
            ];

            $grid_option = [
                'id' => 'detail-list',
                'attributes' => $attribute,
                'active_column' => FALSE,
                    // 'actions' => []
            ];

            echo Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
            ?>
        </div>
    </div>
</div>