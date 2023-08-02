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

<?php $form = ActiveForm::begin(); ?>
<div class="panel-body">
    <div class="panel-subheading">
        <div class="row">
            <div class="col-sm-12">
            <?= Yii::$app->dropdown->dropdownStatic('disp_in', $model, $form, 'col-sm-2 form-group', $model->getAttributeLabel('milk_dispatch_in'), false, 'milk_dispatch_in', false); ?>

            <?= $form->field($model, 'headload_km', ['options' => ['class' => 'form-group col-sm-2']])->textInput() ?>

            <?= Yii::$app->dropdown->dropdownStatic('qty_mode', $model, $form, 'col-sm-2 form-group', $model->getAttributeLabel('milk_dispatch_quantity_mode'), false, 'milk_dispatch_quantity_mode', false); ?>

            <?= Yii::$app->dropdown->dropdownStatic('qty_mode', $model, $form, 'col-sm-2 form-group', $model->getAttributeLabel('milk_receipt_quantity_mode'), false, 'milk_receipt_quantity_mode', false); ?>

            <div class="form-group padding_top_20">
                <?php
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'Save & Next'),
                    'id' => 'dispatch-and-receipt',
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['/configuration/tbl-dcs-general-config/dispatch-and-receipt']),
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
</div>

<?php ActiveForm::end(); ?>
