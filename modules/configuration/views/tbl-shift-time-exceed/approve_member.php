<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('view', 'Shift Time Exceed Provision Approval');
$member_provisional = $model->shiftTimeExceedProvision;
$approval_detail = $member_provisional->shiftTimeExceedApproval;
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
                            'value' => Yii::$app->general->getforeignkey($member_provisional->unionCode, 'union_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            ['attribute' => 'plant_code',
                            'value' => Yii::$app->general->getforeignkey($member_provisional->plantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'mcc_plant_code',
                            'value' => Yii::$app->general->getforeignkey($member_provisional->mccPlantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'bmc_code',
                            'value' => Yii::$app->general->getforeignkey($member_provisional->bmcCode, 'bmc_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'dcs_code',
                            'value' => Yii::$app->general->getforeignkey($member_provisional->dcsCode, 'dcs_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'org_type',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'shift_time_exceed_code',
                            'value' => $member_provisional->shift_time_exceed_code,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'shift_code',
                            'value' => isset($member_provisional->shiftCode) ? $member_provisional->shiftCode->shift : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'org_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'standard_time',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'exceed_time',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'remarks',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'status',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'status_datetime',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'date_time_of_collection',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'status_by',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'status_remarks',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'created_at',
                            'value' => Yii::$app->controls->view_date($member_provisional->created_at),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
            ];
            echo DetailView::widget([
                'model' => $member_provisional,
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
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Previous Approval Detail') ?></h4>
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
                                <th><?= Yii::t('app', 'Remarks') ?></th>

                            </tr>
                        </thead>
                        <?php foreach ($approval_detail as $approval) { ?>
                            <tr>
                                <td><?= $approval->level; ?></td>
                                <td><?= $approval->approval_mode; ?></td>
                                <td><?= Yii::$app->general->getforeignkey($approval->userCode, 'name') ?></td>
                                <td><?= Yii::$app->general->getforeignkey($approval->updatedBy, 'name') ?></td>
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
                                <td><?= $approval->remarks; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>       
        </div>

        <div class="col-md-12 padding_10_0 theme-box mt10">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Add Approval Detail') ?></h4>
            </div>
            <div class="form-grid">
                <div class="col-sm-12">
                    <?php
                    $form = ActiveForm::begin([
                                'validateOnBlur' => false,
                                'validateOnChange' => FALSE,
                                'enableClientValidation' => true,
                                'validateOnSubmit' => true,
                                'fieldConfig' => [
                    ]]);
                    ?>
                    <?php echo $form->errorSummary($model); ?>
                    <div class="row">
                        <div class="col-sm-2 number-validate">
                            <?= $form->field($member_provisional, 'exceed_time')->textInput(['type' => 'time']) ?>
                        </div>
                        <div class="col-sm-2">
                            <?= Yii::$app->dropdown->dropdownStatic('provisional_approval_status', $model, $form, '', $model->getAttributeLabel('status'), false, 'status', FALSE, FALSE, FALSE); ?>
                        </div>
                        <div class="col-sm-2">
                            <?= $form->field($model, 'remarks')->textarea(); ?>
                        </div>
                        <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                            <div class="form-group">
                                <?= Yii::$app->controls->save('save', $model); ?>
                                <?= Yii::$app->controls->reset(); ?>
                                <?= Yii::$app->controls->custombutton('cancel', 'pending-approval'); ?>
                            </div>  
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
