<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use webvimark\modules\UserManagement\components\GhostHtml;

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
                                'attribute' => 'file_name',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'source_type',
                                'value' => isset(Yii::$app->dropdown->getRecords('file_type')['data'][$model->source_type]) ? Yii::$app->dropdown->getRecords('file_type')['data'][$model->source_type] : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'total_record',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'processed_record',
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
                ['attribute' => 'farmerid', 'filter' => false],
                ['attribute' => 'farmername', 'filter' => false],
                ['attribute' => 'dtdate', 'label' => Yii::t('app', 'Date'), 'value' => function($model) {
                        return Yii::$app->controls->view_date($model->dtdate);
                    }, 'filter' => false],
                ['attribute' => 'shift', 'filter' => false],
                ['attribute' => 'milktype', 'filter' => false],
                ['attribute' => 'sampleno', 'filter' => false],
                ['attribute' => 'qty', 'filter' => false],
                ['attribute' => 'fat', 'filter' => false],
                ['attribute' => 'snf', 'filter' => false],
                ['attribute' => 'rate', 'filter' => false],
                ['attribute' => 'amt', 'filter' => false],
                ['attribute' => 'water', 'filter' => false],
                ['attribute' => 'sampletime', 'value' => function($model) {
                        return Yii::$app->controls->view_datetime($model->sampletime);
                    }, 'filter' => false],
                ['attribute' => 'qtymode', 'filter' => false],
                ['attribute' => 'txflag', 'filter' => false],
                ['attribute' => 'response_msg', 'filter' => false],
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