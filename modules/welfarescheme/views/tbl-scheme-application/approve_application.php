<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('view', 'Scheme Application Approval');
$application = $model->schemeApplication;
$documents = $application->applicationDocuments;
$approval_detail = $application->applicationApproval;
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
                            'value' => Yii::$app->general->getforeignkey($application->unionCode, 'union_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            ['attribute' => 'bmc_code',
                            'value' => Yii::$app->general->getforeignkey($application->bmcCode, 'bmc_name'),
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
                            'value' => Yii::$app->general->getforeignkey($application->schemeId, 'scheme_name'),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'customer_type',
                            'value' => isset($application->customer_type) ? (strtolower($application->customer_type) == 'member' ? 'Member' : Yii::$app->general->getforeignkey($application->customerType, 'customer_desc') ) : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'customer_name',
                            'value' => isset($application->customer_type) ? Yii::$app->general->getCustomer($application, $application->customer_type) : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'ex_code',
                            'value' => isset($application->customer_type) ? Yii::$app->general->getCustomer($application, $application->customer_type, true) : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'customer_code',
                            'value' => isset($application->customer_type) ? Yii::$app->general->getCustomer($application, $application->customer_type, FALSE, FALSE, true) : '',
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'application_date',
                            'value' => Yii::$app->controls->view_date($application->application_date),
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'scheme_value',
                            'label' => Yii::t('app', 'Value(Scheme/Approved)'),
                            'value' => $application->scheme_value . '/' . $application->approved_value,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'min_pouring_day',
                            'label' => Yii::t('app', 'Day(min/act)'),
                            'value' => $application->min_pouring_day . '/' . $application->actual_pouring_day,
                            'valueColOptions' => ['style' => 'width:30%']
                        ],
                            [
                            'attribute' => 'min_pouring_qty',
                            'label' => Yii::t('app', 'Qty(min/act)'),
                            'value' =>
                            $application->min_pouring_qty . '/' . $application->actual_pouring_qty,
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
                'model' => $application,
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
                                <td width='60%'><?= Yii::$app->general->getforeignkey($doc->documentMasterCode, 'doc_name'); ?></td>
                                <td width='40%'><?= Html::a('<i class="fa fa-eye"></i>', Url::to('web/welfarescheme/' . $doc->file_name), ['target' => '_blank']) ?></td>
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
                            <?= Yii::$app->dropdown->dropdownStatic('ws_approval_status', $model, $form, '', $model->getAttributeLabel('application_status'), false, 'application_status', FALSE, FALSE, FALSE); ?>
                        </div>
                        <div class="col-sm-2 number-validate">
                            <?= $form->field($model, 'approved_value')->textInput(['readOnly' => true]); ?>   
                        </div>
                        <div class="col-sm-2">
                            <?= $form->field($model, 'status_remarks')->textarea(); ?>
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






