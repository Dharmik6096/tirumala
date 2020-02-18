
<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Head Load');
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
                                'attribute' => 'union_code',
                                'label' => Yii::t('app', 'Union'),
                                'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'head_load_code',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                            [
                                'attribute' => 'criteria_description',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'fix_value',
                                'format' => 'html',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'min_km',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'min_qty',
                                'format' => 'html',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'max_qty',
                                'valueColOptions' => ['style' => 'width:30%'],
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'is_active',
                                'label' => Yii::t('app', 'Status'),
                                'value' => GeneralFunctions::getRecordStatus($model->is_active),
                            ],
                        ],
                    ],
                ];
// View file rendering the widget
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => $attributes,
                    'mode' => 'view',
                    'bordered' => false,
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

        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"><?= Yii::t('app', 'Head Load Transaction') ?></h5></div>
        <div class="form-grid">
            <?php echo $this->render('@app/modules/vsp/views/tbl-head-load-transaction/_form_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
        </div>

        <div class="col-sm-12 view-subtitle"><h5 class="panel-subtitle"><?= Yii::t('app', 'Head Load Applicability') ?></h5></div>
        <div class="form-grid">              
            <?php echo $this->render('_grid_applicability', ['dataProvider' => $appdataProvider, 'searchModel' => $appsearchModel]); ?>
        </div>

    </div>
</div>