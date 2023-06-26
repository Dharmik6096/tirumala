<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;
use app\modules\usermanagement\components\GhostHtml;

$this->title = 'Staff Salary';
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
                                'attribute' => 'staff_member_code',
                                'value' => Yii::$app->general->getforeignkey($model->staffMemberCode, 'staff_member_name'),
                                'valueColOptions' => ['style' => 'width:30%'],
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
                                'attribute' => 'total_value',
                                'label' => Yii::t('app', 'Net Payable Amount (Rs.)'),
                                'value' => Yii::$app->general->decimalformat($model->addition - $model->deduction),
                                'valueColOptions' => ['style' => 'width:80%']
                            ],
                        ],
                    ],
                    [
                        'columns' => [
                            [
                                'attribute' => 'addition',
                                'label' => Yii::t('app', 'Total Addition'),
                                'value' => Yii::$app->general->decimalformat($model->addition),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                            [
                                'attribute' => 'deduction',
                                'label' => Yii::t('app', 'Total Deduction'),
                                'value' => Yii::$app->general->decimalformat($model->deduction),
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

        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Additions / Deductions') ?></h4>
            </div>
        <div class="form-grid">
            <?=
            $this->render('_salary_transaction', [
                'dataProvider' => $dataProvider, 'searchModel' => $searchModel
            ])
            ?>
        </div>

    </div>
</div>