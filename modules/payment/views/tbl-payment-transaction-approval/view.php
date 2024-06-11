<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('view', 'Member Provisional Approval');
$approval_detail = $model->processApprovalCode;
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body">
        <div class="panel-heading">
            <?= Html::encode($this->title) ?>
        </div>
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
                            'attribute' => 'from_date',
                            'value' => Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date),
                            'valueColOptions' => ['style' => 'width:30%'],
                            'label' => Yii::t('app', 'Payment Cycle')
                        ],
                            [
                            'attribute' => 'payment_date',
                            'value' => Yii::$app->controls->view_date($model->payment_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'kg_fat',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'kg_snf',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],

                [
                    'columns' => [
                            [
                            'attribute' => 'avg_fat',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'avg_snf',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                            [
                            'attribute' => 'avg_rate',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'qty',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'total_amount',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'total_deduction',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'final_amount',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'total_count',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'approval_status',
                            'valueColOptions' => ['style' => 'width:80%']
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
        <div class="col-md-12 padding_10_0 theme-box mt10">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Payment Transaction Approval Detail') ?></h4>
            </div>
            <div class="form-grid">
                <div class="col-sm-12">
                    <table class="table table-bordered table-striped table-main table-language table-rate">
                        <tbody>
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Level') ?></th>
                                <th><?= Yii::t('app', 'Mode') ?></th>
                                <th><?= Yii::t('app', 'User') ?></th>
                                <th><?= Yii::t('app', 'Login Type') ?></th>
                                <th><?= Yii::t('app', 'Status By') ?></th>
                                <th><?= Yii::t('app', 'Status') ?></th>
                                <th><?= Yii::t('app', 'Date') ?></th>
                                <th><?= Yii::t('app', 'Remarks') ?></th>

                            </tr>
                        </thead>
                        <?php foreach ($approval_detail as $approval) { ?>
                            <tr>
                                <td><?= $approval->level; ?></td>
                                <td><?= $approval->approval_mode; ?></td>
                                <td><?= Yii::$app->general->getforeignkey($approval->userCode, 'name') ?></td>
                                <td><?= $approval->login_type; ?></td>
                                <td><?= Yii::$app->general->getforeignkey($approval->updatedBy, 'name') ?></td>
                                <td>
                                    <?php
                                    if ($approval->status == '1') {
                                        $approval->status = 'Approve';
                                    } else {
                                        $approval->status = 'Pending';
                                    }
                                    echo $approval->status;
                                    ?>
                                </td>
                                <td><?= Yii::$app->controls->view_datetime($approval->created_at); ?></td>
                                <td><?= $approval->remarks; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>       
        </div>
    </div>
</div>
