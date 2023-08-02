<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsconfiguration\models\TblDcsGeneralConfigDefault */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
<div class="panel-body">
    <div class="panel-subheading">
        <div class="row">

            <?= $form->field($model, 'backup_path', ['options' => ['class' => 'form-group d-contents'], 'template' => '<div class="pull-left">{label}</div><div class="col-sm-2 pb-2">{input}</div>{error}{hint}',])->textInput(['maxlength' => true]) ?>
            <div class='clearfix'></div>

            <div class='row d-contents'>
                <?= Yii::t('app', 'Backup on closing application') ?>
                <div class="col">
                    <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_backup_on_closing'); ?>
                </div>
            </div>

            <div class='row ps-0'>
                <?= Yii::t('app', 'Confirm with user to initiate backup') ?>
                <div class="col">
                    <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_backup_user_choice'); ?>
                </div>
            </div>

            <?= $form->field($model, 'backup_per_shift', ['options' => ['class' => 'form-group d-contents'], 'template' => '<div class="pull-left">{label}</div><div class="col-sm-2 pb-2">{input}</div>{error}{hint}',])->textInput() ?>
            <div class='clearfix'></div>
            <div class='row ps-0'>
                <?= Yii::t('app', 'Backup on payment disbursement') ?>
                <div class="col">
                    <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_backup_disbursement'); ?>
                </div>
            </div>
            <div class="form-group">
                <?php
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'Save & Next'),
                    'id' => 'backup',
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['backup']),
                        'beforeSend' => new JsExpression("function(data){  
                                        $('#loadercontent').show();
                                        $('#pageloader').show();
                                    }"),
                        'success' => new JsExpression('function(data){ 
                                                    if (data.status == "success"){
                                                        window.location="' . \Yii::$app->request->getHostInfo() . '"+data.url;
                                                    }else{
                                                        $(\'#loadercontent\').hide();
                                                        $(\'#pageloader\').hide();
                                                        var cnt=0;
                                                        $.each(data, function(key, val) {
                                                            var parent_div = $("#"+key).parent("div");
                                                            parent_div.find(".help-block").remove();
                                                            $("#"+key).after("<div class=\"help-block\">"+val+"</div>");
                                                            $("#"+key).closest(".form-group").addClass("has-error");                                          
                                                        });

                                                    }
                                     }'),
                    ],
                    'options' => ['class' => 'btn btn-primary',
                        'type' => 'submit'],
                ]);
                AjaxSubmitButton::end();
                ?>
            </div>

        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
