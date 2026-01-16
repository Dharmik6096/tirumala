<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('view', 'Member Provisional Approval');
$member_provisional = $model->memberProvisional;
$documents = $member_provisional->memberPrivisionalDocuments;
$approval_detail = $member_provisional->memberPrivisionalApproval;
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
                            ['attribute' => 'bmc_code',
                            'value' => Yii::$app->general->getforeignkey($member_provisional->tblDcsBmc, 'bmc_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'member_code',
                            'value' => $member_provisional->member_code,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'reference_code',
                            'value' => Yii::$app->general->getforeignkey($member_provisional->dcsCode, 'dcs_code_ex') . $member_provisional->ex_member_code,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'pro_ex_member_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'ex_member_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'created_at',
                            'value' => Yii::$app->controls->view_date($member_provisional->created_at),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'provisional_member_code',
                            'value' => $member_provisional->provisional_member_code,
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
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Document Detail') ?></h4>
            </div>
            <div class="form-grid">
                <div class="col-sm-12">
                    <table class="table table-bordered table-striped table-main table-language table-rate">
                        <tbody>
                        <thead>
                            <tr>
                                <th width='60%'><?= Yii::t('app', 'Document Name') ?></th>
                                <td width='40%'>
                                    <?php
                                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View All Attachments', 'target' => '_blank'];
                                    echo Html::a('<i class="fa fa-eye"></i>', ['view-attachment', 'id' => $model->memberProvisional->provisional_member_code], $options)
                                    ?>
                                </td>
                            </tr>
                        </thead>
                        <?php foreach ($documents as $doc) { ?>
                            <tr>
                                <td width='60%'><?= Yii::$app->general->getforeignkey($doc->docId, 'doc_name'); ?></td>
                                <td width='40%'><?= Html::a('<i class="fa fa-eye"></i>', Url::to(!empty($doc->attachment) ? $doc->attachment : ''), ['target' => '_blank']) ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>       
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
                                <th><?= Yii::t('app', 'Login Type') ?></th>
                                <th><?= Yii::t('app', 'Department') ?></th>
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
                                <td><?= Yii::$app->general->getforeignkey($approval->userCode, 'name') ?></td>
                                <td><?= $approval->login_type; ?></td>
                                <td><?= Yii::$app->general->getforeignkey($approval->departmentId, 'department') ?></td>
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
                                <?= Yii::$app->controls->custombutton('cancel', 'pending-approval', '', 'btn-login'); ?>
                            </div>  
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
