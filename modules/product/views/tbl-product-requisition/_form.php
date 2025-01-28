<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'create-product-sale-form'],
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblproductrequisition-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblproductrequisition-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('product_requisition_type', $model, $form, 'form-group', $model->getAttributeLabel('vendor_type'), false, 'vendor_type', false); ?>
        <?php // Yii::$app->dropdown->customer_type($model, $form, 'tblproductrequisition-bmc_code', 'vendor_type', TRUE, FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblproductrequisition-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'req_date', '', true); ?>
    </div>
    <div class="col-sm-2">
        <?=
        $form->field($model, 'req_time')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '99:99',])->label('Requisition Time (24 Hrs)');
        ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblproductrequisition-bmc_code', 'dcs_code', Yii::t('app', 'DCS')); ?>
        <?php // Yii::$app->dropdown->customer_code($model, $form, 'tblproductrequisition-bmc_code,tblproductrequisition-vendor_type', 'vendor_code', TRUE, FALSE); ?>
    </div>

    <div class="clearfix"></div>
    <?= $form->field($model, 'description', ['options' => ['class' => 'form-group col-sm-2']])->textArea() ?>

    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?php
            AjaxSubmitButton::begin([
                'label' => Yii::t('app', 'Next'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to(['create']),
                    'beforeSend' => new JsExpression("function(data){
                                                    $('.error-summary').hide();
                                                    $('#loadercontent').show();
                                                    $('#pageloader').show();
                                                }"),
                    'success' => new JsExpression('function(data){
                                                    $("#loadercontent").hide();
                                                    $("#pageloader").hide();
                                                    if (data.status == "success"){ 
                                                        var productRequisition = [];
                                                        productRequisition = data.object;
                                                        localStorage.setItem("productRequisition", JSON.stringify(productRequisition));
                                                        window.location="' . \Yii::$app->request->getHostInfo() . '"+data.url;
                                                    }else{
                                                        $("div.help-block").html("");
                                                        $(".form-group").removeClass("has-error");
                                                        $.each(data, function(key, val) {
                                                            $(".field-"+key+" .help-block").remove();
                                                            $(".field-"+key).append("<div class=\"help-block\">"+val+"</div>");
                                                            $(".field-"+key+".form-group").addClass("has-error");
                                                        });
                                                    }
                                                }'),
                ],
                'options' => ['class' => 'btn btn-default btn-raised',
                    'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
            ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
