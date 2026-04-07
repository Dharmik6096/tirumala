<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Manual Collection Request');
$approval_detail = $model->collectionApproval;
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
                            ['attribute' => 'plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            ['attribute' => 'mcc_plant_code',
                            'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
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
                            ['attribute' => 'dcs_code',
                            'label' => Yii::t('app', 'DCS').' Ref Code',
                            'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            ['attribute' => 'dcs_code',
                            'label' => Yii::t('app', 'DCS').' Name',
                            'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            ['attribute' => 'bmc_code',
                            'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            ['attribute' => 'from_date',
                            'value' => Yii::$app->controls->view_date($model->from_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            ['attribute' => 'from_shift',
                            'value' => Yii::$app->general->getforeignkey($model->fromShift, 'shift'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            ['attribute' => 'to_date',
                            'value' => Yii::$app->controls->view_date($model->to_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            ['attribute' => 'to_shift',
                            'value' => Yii::$app->general->getforeignkey($model->toShift, 'shift'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            ['attribute' => 'table_name',
                            'value' => !empty($model->table_name) ? ucwords(str_replace('_', ' ', preg_replace('/^tbl_/', '', $model->table_name))): '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],        
                    ],
                ],
                    [
                    'columns' => [
                            ['attribute' => 'entry_type',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            ['attribute' => 'application_type',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            ['attribute' => 'is_weight_manual',
                            'value' => isset($model->is_weight_manual) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual] : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            ['attribute' => 'is_quality_manual',
                            'value' => isset($model->is_quality_manual) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_quality_manual] : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'is_approved',
                            'value' => isset($model->is_approved) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_approved] : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'approval_status',
                            'value' => isset($model->approval_status) ? Yii::$app->dropdown->getRecords('manual_approve_status')['data'][$model->approval_status] : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'remark',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'allow_manual_collection_code',
                            'value' => $model->allow_manual_collection_code,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                                ['attribute' => 'action_perform',
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

        <?php if (!empty($approval_detail)) { ?>
            <div class="col-md-12 padding_10_0 theme-box mt10">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                    <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Manual Collection Approval Detail') ?></h4>
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
                                    <th><?= Yii::t('app', 'Department') ?></th>
                                    <th><?= Yii::t('app', 'Remarks') ?></th>
                                </tr>
                            </thead>
                            <?php foreach ($approval_detail as $approval) { ?>
                                <tr>
                                    <td><?= $approval->level; ?></td>
                                    <td><?= $approval->approval_mode; ?></td>
                                    <?php if ($model->originating_org_type == 'MOBILE') { ?>
                                        <td><?= Yii::$app->general->getforeignkey($approval->manualCollectionUserCode, 'firstname') ?></td>
                                    <?php } else { ?>
                                        <td><?= Yii::$app->general->getforeignkey($approval->userCode, 'name') ?></td>
                                    <?php } ?>
                                    <td><?= $approval->login_type; ?></td>
                                    <?php if ($model->originating_org_type == 'MOBILE') { ?>
                                        <td><?= Yii::$app->general->getforeignkey($approval->manualCollectionUpdatedBy, 'firstname') ?></td>
                                    <?php } else { ?>
                                        <td><?= Yii::$app->general->getforeignkey($approval->updatedBy, 'name') ?></td>
                                    <?php } ?>
                                    <td>
                                        <?php
                                        if ($approval->status == '1') {
                                            $approval->status = 'Approve';
                                        } else if ($approval->status == '2') {
                                            $approval->status = 'Reject';
                                        } else {
                                            $approval->status = 'Pending';
                                        }
                                        echo $approval->status;
                                        ?>
                                    </td>
                                    <td><?= Yii::$app->controls->view_datetime($approval->created_at); ?></td>
                                    <td><?= $approval->department; ?></td>
                                    <td><?= $approval->remarks; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>       
            </div>
        <?php } ?>
        <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading accordion" data-toggle="collapse" data-target="#master_attendance_grid" aria-expanded="true" aria-controls="attendance_grid">
                    Manual Collection Request Attachment
                </h4>
            </div>
            <div class="col-sm-12 collapse in" id="master_attendance_grid">
                <?=
                $this->render('_attachment_list', [
                    'dataProviderOther' => $dataProviderOther,
                    'attachment' => $attachment,
                ])
                ?>
            </div>
        </div>
    </div>
</div>
