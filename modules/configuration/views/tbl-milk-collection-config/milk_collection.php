<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsconfiguration\models\TblMilkCollectionConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$form = ActiveForm::begin([
            'method' => 'post',
            'enableAjaxValidation' => false,
            'validateOnBlur' => true,
        ]);
?>

<div class="panel-body">
    <div class="panel-subheading">
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-6 padding_left_10 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading"></h4>
                </div>

                <?= $form->field($model, 'can_per_ltr', ['options' => ['class' => 'form-group col-sm-4']])->textInput() ?>

                <?= $form->field($model, 'can_warning_per', ['options' => ['class' => 'form-group col-sm-4']])->textInput() ?>

                <?= $form->field($model, 'ltr_to_kg', ['options' => ['class' => 'form-group col-sm-4']])->textInput() ?>
                <div class='col-sm-12 mt-4'>
                    <p class="float-start"><?= Yii::t('app', 'CLR Formula: (SNF -(FAT * ') ?></p>
                    <?= $form->field($model, 'lr1_for_clr', ['options' => ['class' => 'form-group pull-left mt-8 col-xs-6 col-md-2'], 'template' => '{input}{error}{hint}',])->textInput() ?>
                    <p class='float-start'>) - </p>
                    <?= $form->field($model, 'lr2_for_clr', ['options' => ['class' => 'form-group pull-left mt-8 col-xs-6 col-md-2'], 'template' => '{input}{error}{hint}',])->textInput() ?>
                    <p class='pull-left'>) * 4</p>
                </div>

                <div class='clearfix'></div>
                <?= Yii::$app->dropdown->dropdownStatic('default_snf', $model, $form, 'col-sm-4 form-group', $model->getAttributeLabel('default_snf'), false, 'default_snf', false); ?>

                <?= $form->field($model, 'default_snf_value', ['options' => ['class' => 'form-group col-sm-4']])->textInput() ?>
            </div>

            <div class="col-md-6 padding_10_0 theme-box theme_border_left">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading"></h4>
                </div>
                <?= $form->field($model, 'variation_in_qty', ['options' => ['class' => 'form-group col-sm-4']])->textInput() ?>
                <div class="form-group col-sm-4 mt-4">
                    <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'variation_in_qty_block'); ?>
                </div>
                <?= Yii::$app->dropdown->dropdownStatic('weight_setting', $model, $form, 'col-sm-4 form-group ', $model->getAttributeLabel('weight_setting'), false, 'weight_setting', false); ?>
                <div class='clearfix'></div>
                <?= $form->field($model, 'variation_in_fat', ['options' => ['class' => 'form-group col-sm-4']])->textInput() ?>
                <div class="form-group col-sm-4 mt-4">
                    <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'variation_in_fat_block'); ?>
                </div>
                <?= Yii::$app->dropdown->dropdownStatic('quality_setting', $model, $form, 'col-sm-4 form-group', $model->getAttributeLabel('quality_setting'), false, 'quality_setting', false); ?>

                <div class='clearfix'></div>
                <?= $form->field($model, 'variation_in_snf', ['options' => ['class' => 'form-group col-sm-4']])->textInput() ?>
                <div class="form-group col-sm-4 mt-4">
                    <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'variation_in_snf_block'); ?>
                </div>
                <?= Yii::$app->dropdown->dropdownStatic('dispatch_setting', $model, $form, 'col-sm-4 form-group', $model->getAttributeLabel('dispatch_setting'), false, 'dispatch_setting', false); ?>
                <div class='clearfix'></div>
                <?= $form->field($model, 'sample_milk_size', ['options' => ['class' => 'form-group col-sm-4']])->textInput() ?>
                <?= Yii::$app->dropdown->dropdownStatic('collection_mode', $model, $form, 'col-sm-4 form-group', $model->getAttributeLabel('collection_mode'), false, 'collection_mode', false); ?>
            </div>

            <div class="col-md-6 padding_left_10 theme-box theme_border_right">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading"></h4>
                </div>
                <div class="form-group col-sm-12">
                    <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'accept_milk'); ?>
                </div>
                <div class='clearfix'></div>
                <div class='pull-left col-sm-4'>
                    <?= Yii::t('app', 'Allow multiple entry for member collection ') ?>&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
                <div class="form-group col-sm-4">
                    <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'multi_entry_diff_milk_type'); ?>
                </div>
                <div class="form-group col-sm-4">
                    <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'multi_entry_same_milk_type'); ?>
                </div>
                <div class='clearfix'></div>
                <div class="form-group col-sm-12">
                    <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'seperate_can'); ?>
                </div>
                <div class='clearfix'></div>
                <?= Yii::$app->dropdown->dropdownStatic('qty_mode', $model, $form, 'col-sm-4 form-group', $model->getAttributeLabel('collection_quantity_mode'), false, 'collection_quantity_mode', false); ?>

                <?= Yii::$app->dropdown->dropdownStatic('qty_mode', $model, $form, 'col-sm-4 form-group', $model->getAttributeLabel('bmc_collection_quantity_mode'), false, 'bmc_collection_quantity_mode', false); ?>

                <?= Yii::$app->dropdown->dropdownStatic('qty_mode', $model, $form, 'col-sm-4 form-group', $model->getAttributeLabel('local_milk_sale_quantity_mode'), false, 'local_milk_sale_quantity_mode', false); ?>

                <?= Yii::$app->dropdown->dropdownStatic('qty_mode', $model, $form, 'col-sm-4 form-group', $model->getAttributeLabel('sample_milk_quantity_mode'), false, 'sample_milk_quantity_mode', false); ?>

                <div class='clearfix'></div>
                <hr />
            </div>

            <div class="col-md-6 padding_10_0 theme-box">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading"><?= Yii::t('app', 'Average Parameter Capture') ?></h4>
                </div>
                <p>&nbsp;</p>
                <div class='col-sm-4'>
                    <p><?= Yii::t('app', 'When machine out of order') ?></p>
                    <?= Yii::$app->dropdown->dropdownStatic('based_on', $model, $form, 'form-group', $model->getAttributeLabel('based_on'), false, 'based_on', false); ?>

                    <?= Yii::$app->dropdown->dropdownStatic('shift_type', $model, $form, 'form-group', $model->getAttributeLabel('shift_code'), false, 'shift_code', false); ?>

                    <?= $form->field($model, 'no', ['options' => ['class' => 'form-group']])->textInput() ?>
                </div>
                <div class='col-sm-4'>
                    <p><?= Yii::t('app', 'For Display Purpose') ?></p>
                    <?= Yii::$app->dropdown->dropdownStatic('based_on', $model, $form, 'form-group', $model->getAttributeLabel('based_on_disp'), false, 'based_on_disp', false); ?>

                    <?= Yii::$app->dropdown->dropdownStatic('shift_type', $model, $form, 'form-group', $model->getAttributeLabel('shift_code_disp'), false, 'shift_code_disp', false); ?>

                    <?= $form->field($model, 'no_disp', ['options' => ['class' => 'form-group']])->textInput() ?>
                </div>
                <div class='col-sm-4'>
                    <p><?= Yii::t('app', 'Local Milk Sale') ?></p>
                    <?= Yii::$app->dropdown->dropdownStatic('based_on_local', $model, $form, 'form-group', $model->getAttributeLabel('based_on_local_sale'), false, 'based_on_local_sale', false); ?>

                    <?= $form->field($model, 'no_disp_local_sale', ['options' => ['class' => 'form-group']])->textInput() ?>

                    <?= $form->field($model, 'per_local_sale', ['options' => ['class' => 'form-group']])->textInput() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <?php
    AjaxSubmitButton::begin([

        'label' => Yii::t('app', 'Save & Next'),
        'id' => 'milk-collection',
        'ajaxOptions' => [
            'type' => 'POST',
            'url' => Url::to(['milk-collection']),
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

<?php
$script = "
    $(document).ready(function() {
        $('#radio').hide();
        if($('select#tblmilkcollectionconfig-default_snf').val() == 0){
            $('#tblmilkcollectionconfig-default_snf_value').val('');
            $('#tblmilkcollectionconfig-default_snf_value').attr('disabled','disabled');
        }
        if($('select#tblmilkcollectionconfig-default_snf').val() == 1){
            $('.params  input[type=checkbox]').each(function () {
                var label_text = $(this).closest('label').text();
                if(label_text == 'CLR' || label_text == ' CLR' || label_text == 'SNF' || label_text == ' SNF'){
                    $('label').each(function(){
                        var label_text = $(this).text();
                        if(label_text == 'SNF' || label_text == ' SNF' || label_text == 'CLR' || label_text == ' CLR'){
                            $(this).find('input').attr('disabled','disabled')
                            $(this).find('input').prop('checked', false); 
                        }
                    });
                }
            });
        }
        check();
        function check(){
            $('.params  input[type=checkbox]').each(function () {
                if(!this.checked){
                    var label_text = $(this).closest('label').text();
                    if(label_text == 'CLR' || label_text == ' CLR'){
                        $('input[type=radio]:checked').each(function(){
                            $(this).prop('checked', false);  
                        });
                        $('#radio').hide();
                    }
                }
                if(this.checked){
                    var label_text = $(this).closest('label').text();
                    if(label_text == 'CLR' || label_text == ' CLR'){
                        $('label').each(function(){
                            var label_text = $(this).text();
                            if(label_text == 'SNF' || label_text == ' SNF'){
                                var value = $(this).find('input').attr('disabled','disabled');
                                $(this).find('input').prop('checked', false); 
                            }
                        });
                        $('#radio').show();
                        $('#tblmilkcollectionconfig-input_clr').prop('checked', true); 
                    }
                    if(label_text == 'SNF' || label_text == ' SNF'){
                        $('label').each(function(){
                            var label_text = $(this).text();
                            if(label_text == 'CLR' || label_text == ' CLR'){
                                var value = $(this).find('input').attr('disabled','disabled');
                                $(this).find('input').prop('checked', false); 
                            }
                        });
                    }
                }
            });
        }

        $('select#tblmilkcollectionconfig-default_snf').change(function(){
            $('input').removeAttr('disabled','disabled');
            if($(this).val() == '0'){
                $('#tblmilkcollectionconfig-default_snf_value').val('');
                $('#tblmilkcollectionconfig-default_snf_value').attr('disabled','disabled');
            }else{
                $('label').each(function(){
                    var label_text = $(this).text();
                    if(label_text == 'SNF' || label_text == 'CLR' || label_text == ' SNF' || label_text == ' CLR'){
                        var value = $(this).find('input').attr('disabled','disabled');
                        $(this).find('input').prop('checked', false);
                        $('input[type=radio]:checked').each(function(){
                            $(this).prop('checked', false);  
                        });
                        $('#radio').hide();
                    }
                });
            }
        });
        $('#tblmilkcollectionconfig-input_clr').change(function(){
            $('#tblmilkcollectionconfig-from_machine_clr').prop('checked', false);
        });
        $('#tblmilkcollectionconfig-from_machine_clr').change(function(){
            $('#tblmilkcollectionconfig-input_clr').prop('checked', false);
        });
        $('.params input[type=checkbox]').change(function() {
            $('label').each(function(){
                var label_text = $(this).text();
                if(label_text == 'SNF' || label_text == 'CLR' || label_text == ' SNF' || label_text == ' CLR'){
                    var value = $(this).find('input').removeAttr('disabled','disabled')
                }
            });
            check();
	});
    });
";
$this->registerJs($script, View::POS_END);
?>


