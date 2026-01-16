<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\ActiveForm;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

$this->title = Yii::$app->label->title('view', 'Shift Time Exceed Provision Approval');
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
                            'value' => Yii::$app->general->getforeignkey($shiftTimeExceedModel->unionCode, 'union_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'plant_code',
                            'value' => Yii::$app->general->getforeignkey($shiftTimeExceedModel->plantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'mcc_plant_code',
                            'value' => Yii::$app->general->getforeignkey($shiftTimeExceedModel->mccPlantCode, 'name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'bmc_code',
                            'value' => Yii::$app->general->getforeignkey($shiftTimeExceedModel->bmcCode, 'bmc_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'dcs_code',
                            'value' => Yii::$app->general->getforeignkey($shiftTimeExceedModel->dcsCode, 'dcs_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'shift_time_exceed_code',
                            'value' => Yii::$app->general->getforeignkey($shiftTimeExceedModel, 'shift_time_exceed_code'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'org_type',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'org_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'standard_time',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'exceed_time',
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
                            'attribute' => 'shift_code',
                            'value' => Yii::$app->general->getmultiforeignkey($shiftTimeExceedModel, ['shiftCode'], 'shift'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'remarks',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'status',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'status_datetime',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'status_remarks',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'status_by',
                            'valueColOptions' => ['style' => 'width:80%']
                        ],
                    ],
                ],
            ];
            echo DetailView::widget([
                'model' => $shiftTimeExceedModel,
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
                                <th><?= Yii::t('app', 'Department') ?></th>
                                <th><?= Yii::t('app', 'Remarks') ?></th>
                            </tr>
                        </thead>
                        <?php
                        if ($shiftTimeExceedModel) {
                            foreach ($shiftTimeExceedModel->shiftTimeExceedApproval as $approval) {
                                ?>
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
                                    <td><?= $approval->department; ?></td>
                                    <td><?= $approval->remarks; ?></td>
                                </tr>
                            <?php } ?>
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
                                'fieldConfig' => []
                    ]);
                    ?>
                    <?php echo $form->errorSummary($model); ?>
                    <div class="row">
                        <div class="col-sm-2">
                            <?= $form->field($shiftTimeExceedModel, 'exceed_time')->widget(MaskedInput::class, ['mask' => '99:99']); ?>
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