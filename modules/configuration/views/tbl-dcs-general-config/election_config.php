<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsconfiguration\models\TblDcsGeneralConfigDefault */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
<div class="panel-body">
    <div class="panel-subheading">
        <div class="row">
            <?= $form->field($model, 'allow_multiple_voters', ['options' => ['class' => 'form-group '], 'checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
            <div class='clearfix'></div>
            <?= $form->field($model, 'election_term', ['options' => ['class' => 'form-group'], 'template' => '<div class="pull-left">{label}</div><div class="col-sm-3">{input}</div>{error}{hint}',])->textInput() ?>
            <div class='clearfix'></div>
            <?= $form->field($model, 'election_alert_day', ['options' => ['class' => 'form-group'], 'template' => '<div class="pull-left">{label}</div><div class="col-sm-3">{input}</div>{error}{hint}',])->textInput() ?>
            <div class='clearfix'></div>
            <?= $form->field($model, 'nos_of_reminders', ['options' => ['class' => 'form-group'], 'template' => '<div class="pull-left">{label}</div><div class="col-sm-3">{input}</div>{error}{hint}',])->textInput() ?>

            <div class="form-group">
                <?php
                AjaxSubmitButton::begin([

                    'label' => Yii::t('app', 'Save & Next'),
                    'id' => 'election-config',
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['election-config']),
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
