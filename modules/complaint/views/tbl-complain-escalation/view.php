<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::$app->label->title('view', 'Complain Escalation');

?>
<div class="tbl-purchase-rate-view">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= Yii::$app->controls->cancel($model); ?>
            <?= Html::encode($this->title) ?>
        </div>
        <div class="panel-body">
            <div class="form-grid">
                <div class="table-responsive margin-bottom-10">
                    <?php
                    $attributes = [
                            [
                            'columns' => [
                                    [
                                    'attribute' => 'complain_escalation_code',
                                    'valueColOptions' => ['style' => 'width:30%']
                                ],
                                    [
                                    'attribute' => 'union_code',
                                    'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                                    'valueColOptions' => ['style' => 'width:30%']
                                ],
                            ],
                        ],
                            [
                            'columns' => [
                                    [
                                    'attribute' => 'escalation_name',
                                    'valueColOptions' => ['style' => 'width:30%']
                                ],
                                    [
                                    'attribute' => 'escalation_remarks',
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
            <div id="gridcontentSet" class='hide-grid-settings'>
                <?=
                $this->render('_list_grid', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
                ])
                ?>
            </div>

        </div>
    </div>
</div>

