
<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('view', 'Customer Provisional Approval');
$customer_provisional = $model->customerProvisional;
$documents = $customer_provisional->customerPrivisionalDocuments;
$approval_detail = $customer_provisional->customerPrivisionalApproval;
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
                            'value' => Yii::$app->general->getforeignkey($customer_provisional->unionCode, 'union_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            ['attribute' => 'bmc_code',
                            'value' => Yii::$app->general->getforeignkey($customer_provisional->bmcCode, 'bmc_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'customer_provisional_code',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'customer_code_ex',
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
                            'attribute' => 'created_at',
                            'value' => Yii::$app->controls->view_date($customer_provisional->created_at),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
            ];
            echo DetailView::widget([
                'model' => $customer_provisional,
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
                                <th width='40%'><?= Yii::t('app', '') ?></th>
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
                                'id' => 'approve-customer-form',
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
                                <?php
                                echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']);
                                echo Html::button(Yii::t('app', 'Re-Route'), ['class' => 'btn btn-primary apply-shortcut', 'data-toggle' => 'modal', 'data-target' => '#ProvisionalModal',]);
                                ?>
                                <?= Yii::$app->controls->save('save', $model, 'saveBtn'); ?>
                                <?= Yii::$app->controls->reset(); ?>
                                <?= Yii::$app->controls->custombutton('cancel', 'pending-customer-approval', '', 'btn-login'); ?>
                            </div>  
                        </div>
                    </div>
                    <?php
                    echo $this->render('@app/modules/document/views/tbl-attachment/_reroute', ['model' => $model,]);
                    ActiveForm::end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
