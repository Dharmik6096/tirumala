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

<div class="panel-body">
    <div class="panel-subheading">
        <?php $form = ActiveForm::begin(); ?>
        <?php echo $form->errorSummary($model) ?>
        <div class="row">
            <div class="form-group d-contents">
            <?= Yii::t('app', 'Purchase Rate with Tax'); ?>
            <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'purchase_rate_with_tax'); ?>
            </div>
            <div class='clearfix'></div>
            <div class="form-group d-contents">
            <?= Yii::t('app', 'Product Sale Rate'); ?>
            <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'sale_rate_with_tax'); ?>
            </div>
            <div class='clearfix'></div>    

            <div class='d-contents'>
                <?= Yii::t('app', 'Product Sale In Cash') ?>  
                <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'product_sale_in_cash'); ?>
            </div>
            
            <div class="form-group">
                <?php
                AjaxSubmitButton::begin([

                    'label' => Yii::t('app', 'Save & Next'),
                    'id' => 'product-sale-purchase',
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['product-sale-purchase']),
                        'beforeSend' => new JsExpression("function(data){  
                                        $('#loadercontent').show();
                                        $('#pageloader').show();
                                    }"),
                        'success' => new JsExpression('function(data){ 
                                                    if (data.status == "success"){
                                                        window.location="' . \Yii::$app->request->getHostInfo() . '"+data.url;
                                                        $(".error-summary").hide();
                                                        $(".error-summary li").remove();
                                                    }else{
                                                        $(\'#loadercontent\').hide();
                                                        $(\'#pageloader\').hide();
                                                        var cnt=0;
                                                        $.each(data, function(key, val) {
                                                            $(".error-summary").hide();
                                                            $(".error-summary li").remove();
                                                            $.each(data, function(key, val) {
                                                                $(".error-summary ul").append("<li>"+val+"</li>");
                                                            });
                                                            $(".error-summary").show();
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
