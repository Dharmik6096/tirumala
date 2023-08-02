<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\modules\dcsconfiguration\models\TblDcsGeneralConfigDefault */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel-body">
    <div class="panel-subheading">
        <div class="row">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'share_unit_cost', ['options' => ['class' => 'form-group d-flex pb-3'], 'template' => '<div class="pull-left mt-10">{label}</div><div class="col-sm-2">{input}</div>{error}{hint}',])->textInput() ?>
            <div class='clearfix'></div>
            <?= $form->field($model, 'min_share_req', ['options' => ['class' => 'form-group d-flex pb-3'], 'template' => '<div class="pull-left">{label}</div><div class="col-sm-2">{input}</div>{error}{hint}',])->textInput() ?>
            <div class='clearfix'></div>
            <?= $form->field($model, 'share_issued', ['options' => ['class' => 'form-group d-flex pb-3'], 'template' => '<div class="pull-left">{label}</div><div class="col-sm-2">{input}</div>{error}{hint}',])->textInput() ?>
            <div class='clearfix'></div>
            <?= $form->field($model, 'max_share_buy', ['options' => ['class' => 'form-group d-flex'], 'template' => '<div class="pull-left">{label}</div><div class="col-sm-2">{input}</div>{error}{hint}',])->textInput() ?>
            <div class='clearfix'></div>
            <div class="form-group">
                <?php
                    AjaxSubmitButton::begin([
                        'label' => Yii::t('app', 'Save & Next'),
                        'id' => 'share-management',
                        'ajaxOptions' => [
                            'type' => 'POST',
                            'url' => Url::to(['share-management']),
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

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

