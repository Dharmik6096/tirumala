<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('view', 'Scheme Application');
$documents = $model->applicationDocuments;
$approval_detail = $model->applicationApprovalStages;
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body">
        <div class="panel-heading">
            <?= Yii::$app->controls->cancel($model); ?>
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
                            ['attribute' => 'bmc_code',
                            'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'application_id',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'scheme_id',
                            'value' => Yii::$app->general->getforeignkey($model->schemeId, 'scheme_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'customer_type',
                            'value' => isset($model->customer_type) ? (strtolower($model->customer_type) == 'member' ? 'Member' : Yii::$app->general->getforeignkey($model->customerType, 'customer_desc') ) : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'customer_name',
                            'value' => isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type) : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'ex_code',
                            'value' => isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type, true) : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'customer_code',
                            'value' => isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type, FALSE, FALSE, true) : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'application_date',
                            'value' => Yii::$app->controls->view_date($model->application_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'scheme_value',
                            'label' => Yii::t('app', 'Value(Scheme/Approved)'),
                            'value' => $model->scheme_value . '/' . $model->approved_value,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'min_pouring_day',
                            'label' => Yii::t('app', 'Day(min/act)'),
                            'value' => $model->min_pouring_day . '/' . $model->actual_pouring_day,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'min_pouring_qty',
                            'label' => Yii::t('app', 'Qty(min/act)'),
                            'value' =>
                            $model->min_pouring_qty . '/' . $model->actual_pouring_qty,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'status_date',
                            'value' => Yii::$app->controls->view_datetime($model->status_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'application_status',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'remarks',
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
        <div class="col-md-12 padding_10_0 theme-box">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Document Detail') ?></h4>
            </div>
            <div class="form-grid">
                <div class="col-sm-12">
                    <table class="table table-bordered table-striped table-main table-language table-rate">
                        <tbody>
                        <thead>
                            <tr>
                                <th width='60%'><?= Yii::t('app', 'Document Name') ?></th>
                                <th width='40%'><?= Yii::t('app', '') ?></th>
                            </tr>
                        </thead>
                        <?php foreach ($documents as $doc) { ?>
                            <tr>
                                <td width='60%'><?= Yii::$app->general->getforeignkey($doc->documentMasterCode, 'doc_name'); ?></td>
                                <td width='40%'><?= Html::a('<i class="fa fa-eye"></i>', Url::to('web/welfarescheme/' . $doc->file_name), ['target' => '_blank']) ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>       
        </div>
        <div class="col-md-12 padding_10_0 theme-box">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Approval Detail') ?></h4>
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
                                <th><?= Yii::t('app', 'Status By') ?></th>
                                <th><?= Yii::t('app', 'Status') ?></th>
                                <th><?= Yii::t('app', 'Date') ?></th>
                                <th><?= Yii::t('app', 'Approved Value') ?></th>
                                <th><?= Yii::t('app', 'Remarks') ?></th>

                            </tr>
                        </thead>
                        <?php foreach ($approval_detail as $approval) { ?>
                            <tr>
                                <td><?= $approval->level; ?></td>
                                <td><?= $approval->approval_mode; ?></td>
                                <td><?= Yii::$app->general->getforeignkey($approval->userCode, 'name') ?></td>
                                <td><?= Yii::$app->general->getforeignkey($approval->statusBy, 'name') ?></td>
                                <td><?= $approval->application_status; ?></td>
                                <td><?= Yii::$app->controls->view_datetime($approval->status_date); ?></td>
                                <td><?= $approval->approved_value; ?></td>
                                <td><?= $approval->status_remarks; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>       
        </div>
    </div>
</div>   






