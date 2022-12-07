<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\bootstrap\ActiveForm;

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
                            'attribute' => 'customer_type',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                            [
                            'attribute' => 'customer_code',
                            'valueColOptions' => ['style' => 'width:30%'],
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
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Documents Detail') ?></h4>
            </div>
            <div class="form-grid">

            </div>       
        </div>
        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Previous Approval Detail') ?></h4>
            </div>
            <div class="form-grid">

            </div>       
        </div>
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
            <div class="col-sm-2">
                <?= $form->field($model, 'approved_value')->textInput(); ?>   
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'status_remarks')->textarea(); ?>
            </div>
            <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save('save', $model); ?>
                    <?= Yii::$app->controls->reset(); ?>
                    <?= Yii::$app->controls->cancel($model); ?>
                </div>  
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>   






