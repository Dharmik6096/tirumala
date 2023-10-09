<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

$this->title = Yii::t('app', 'Purchase Rate - Manually');

?>

<?php
$form = ActiveForm::begin(['id' => 'manual_form', 'options' => [
                'field-class' => 'form-group col-sm-3'
            ], 'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>

<div class="panel-subheading">
    <h5 class="panel-subtitle"> <?php echo Yii::t('app', 'Purchase Rate - Manual'); ?></h5>
    <div class="row">

        <?php //echo Yii::$app->dropdown->dropdown('quality_param', $purchaseBasedModel, $form, 'form-group col-sm-3', 'Quality Param');  ?>

        <?php
        $method = $purchase_rate->rateType->rate_type;
        $names = explode('+', $method);
        $i = 1;
        $noOf = count($purchaseBasedModel);
        foreach ($purchaseBasedModel as $key => $pModel) {
            ?>
            <div class="panel-subheading <?php echo ($i==1) ? '' : 'mt10'; ?>">
                <div class="row">
                    <?= Html::activeHiddenInput($pModel, '[' . $key . ']quality_param', ['value' => $pModel->getQualityParamId($names[$key])]) ?>

                    <?= $form->field($pModel, '[' . $key . ']quality_param_name', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['value' => $names[$key], 'readonly' => true]) ?>

                    <?= $form->field($pModel, '[' . $key . ']milk_quality_type_code', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($dropDown['milk_quality_type_code'], ['prompt' => 'Select Milk Quality Type']); ?>

                    <?= $form->field($pModel, '[' . $key . ']milk_type_code', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($dropDown['milk_type_code'], ['prompt' => 'Select Milk Type']); ?>

                    <?= $form->field($pModel, '[' . $key . ']start_range', ['options' => ['class' => 'form-group col-sm-2']])->textInput()->label($names[$key].' Start') ?>

                    <?= $form->field($pModel, '[' . $key . ']end_range', ['options' => ['class' => 'form-group col-sm-2']])->textInput()->label($names[$key].' End') ?>

                    <div class="clearfix"></div>

                    <?= $form->field($pModel, '[' . $key . ']kg_rate', ['options' => ['class' => 'form-group col-sm-2']])->textInput() ?>

                    <?= $form->field($pModel, '[' . $key . ']deduction_type', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($dropDown['deduction_type'], ['prompt' => 'Select Deduction Type']); ?>

                    <?= $form->field($pModel, '[' . $key . ']ref_type', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($dropDown['ref_type'], ['prompt' => 'Select Ref. Type']); ?>

                    <?= $form->field($pModel, '[' . $key . ']fixed_point', ['options' => ['class' => 'form-group col-sm-2']])->textInput() ?>

                    <?= $form->field($pModel, '[' . $key . ']value', ['options' => ['class' => 'form-group col-sm-2']])->textInput() ?>

                    <?= Html::activeHiddenInput($pModel, '[' . $key . ']purchase_rate_code', ['value' => $purchase_rate->purchase_rate_code]) ?>
                </div>
            </div>
        
        <?php if($noOf==2 && $key==0){ ?>
            <?= Html::activeHiddenInput($pModel, '[' . $key . ']formula', ['value' => 0]) ?>
        <?php } ?>
        
        <?php $i++; } ?>

        <?= $form->field($pModel, '[' . $key . ']formula', ['options' => ['class' => 'form-group col-sm-2']])->dropDownList([], ['prompt' => 'Select Formula']); ?>
        
        <div class="clearfix"></div>

        <div class="col-sm-12 mt10">
            <?= Yii::$app->controls->save(Yii::t('app', 'Add'), $pModel); ?>
            <?php
            /*AjaxSubmitButton::begin([
                'label' => Yii::t('app', 'Add'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to(['tbl-tanker-rate-based/create']),
                    'success' => new JsExpression('function(data){

                        if (data.status == "success"){
                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-success\'><i class=\'fa fa-check\'></i></div><span>Successfully Added.</span></div></div>");
                                   $("#manual_form")[0].reset();  
                                   $.pjax.reload({container: "#manual-grid"});
                        }else{

                            $.each(data, function(key, val) {
                                $("#"+key).after("<div class=\"help-block\">"+val+"</div>");
                                $("#"+key).closest(".form-group").addClass("has-error");
                            });
                        }
                             }'),
                    'error' => new JsExpression('function(){
                                    
                             }'),
                ],
                'options' => ['class' => 'btn btn-primary',
                    'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();*/
            ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?= $this->render('_manual_rate_grid', ['dataProvider' => $purchaseBasedGrid, 'searchModel' => $purchaseBasedModelSearch]) ?>

<?php

$script = "
    $('#tblpurchaseratebased-".$key."-formula').on('blur',function(){          
            var milkType = '".$purchase_rate->milk_type_code."';
            var rateType = '".$purchase_rate->rate_type."';
            var wefDate = '".$purchase_rate->wef_date."';
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/dcsoperation/formula-master/get-formula']) . "',
                        data: 'wefDate='+wefDate+'&milkType='+milkType+'&rateType='+rateType,
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            $('#tblpurchaseratebased-".$key."-formula').empty();
                            if (obj1.status == 'success')
                            {
                                 $.each(obj1.data, function (key, value) {
                                    $('#tblpurchaseratebased-".$key."-formula').append($('<option></option>').val(key).html(value));
                                 }); 
                            }else
                                $('#tblpurchaseratebased-".$key."-formula').append($('<option></option>').val('').html('Select Formula'));
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });           
    });
";
$this->registerJs($script, View::POS_END, 'village-code');
?>