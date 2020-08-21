<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$readonly = $type == 'create' ? FALSE : TRUE;
$class = $type == 'create' ? '' : 'disabled';
//$error = $type == 'create' ? [$model] : [$model, $detailModel];
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'dpu-incentive-form'],
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">DPU Incentive Master</h4>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2" >
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tbldpuincentivemaster-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, '', $readonly); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbldpuincentivemaster-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, '', $readonly); ?>
        </div>  
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tbldpuincentivemaster-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', '', $readonly); ?>
        </div>
        <div class="col-sm-2 <?= $class ?>">
            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tbldpuincentivemaster-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), FALSE, '', false, TRUE); ?>         
        </div>
        

        <div class="col-sm-1">
            <?=
            $form->field($model, 'm_start_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                'mask' => '99:99',])
            ?> 
        </div>
        <div class="col-sm-1">
            <?=
            $form->field($model, 'm_cutoff_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                'mask' => '99:99',])
            ?> 
        </div>
        <div class="col-sm-1">
            <?=
            $form->field($model, 'm_lock_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                'mask' => '99:99',])
            ?> 
        </div>
        <div class="col-sm-1">
            <?=
            $form->field($model, 'e_start_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                'mask' => '99:99',])
            ?> 
        </div>
        <div class="col-sm-1">
            <?=
            $form->field($model, 'e_cutoff_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                'mask' => '99:99',])
            ?> 
        </div>

        <div class="col-sm-1">
            <?=
            $form->field($model, 'e_lock_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                'mask' => '99:99',])
            ?> 
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($model, 'inc_rate')->textInput() ?>
        </div>
        <div class="col-sm-1 number-validate">
            <?= $form->field($model, 'inc_deduction')->textInput() ?>
        </div>

    <?php if ($type == 'edit') { ?>
        <div class="clearfix"></div>
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">DPU Incentive Detail</h4>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($detailModel, $form, 'from_date', '', date('Y-m-d'), false, false); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($detailModel, $form, 'to_date', '', date('Y-m-d'), false, false); ?>
        </div>
        <div class="col-sm-2 shift">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $detailModel, $form, '', $detailModel->getAttributeLabel('shift_code'), false, 'shift_code'); ?>
        </div> 
        <div class="col-sm-2 reset_field">
            <?=
            $form->field($detailModel, 'from_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                'mask' => '99:99',])
            ?> 
        </div>
        <div class="col-sm-2 reset_field">
            <?=
            $form->field($detailModel, 'to_time')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
                'mask' => '99:99',])
            ?> 
        </div>
        <div class="col-sm-2 reset_field">
            <?= Yii::$app->dropdown->dropdownStatic('calc_type', $detailModel, $form, 'form-group', TRUE, false, 'scheme_type', false); ?>  
        </div>
        <div class="col-sm-2 number-validate reset_field">
            <?= Html::activeHiddenInput($detailModel, 'incentive_deduction_id', ['value' => $detailModel->incentive_deduction_id]) ?>
            <?= $form->field($detailModel, 'amount')->textInput() ?>
        </div>
    <?php } ?>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?php if ($type == 'edit') { ?> 
                <?php
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'Save'),
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['update', 'id' => $model->incentive_master_code]),
                        'beforeSend' => new JsExpression("function(data){
                                               $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                        'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");                                                                   
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    reloadGrid();
                                                                    $(".transporter").hide();
                                                                    $("#dpu-incentive-form .reset_field input").val("");
                                                                    $("#dpu-incentive-form .reset_field select").val("");
                                                                    $("#dpu-incentive-form .reset_field textarea").val("");

                                                                    $(".panel-body").scrollTop(0);                                                                    
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>", function(result){
                                                                   setTimeout(function(){
                                                                   $("#tblcollectionincentivededuction-from_time").focus();},100);
                                                                    });
                                                                }else{
                                                                
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data, function(key, val) {
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                    });
                                                                    $(".error-summary").show();
                                                                     $(window).scrollTop(0);
                                                                }
                                                 }'),
                    ],
                    'options' => ['class' => 'btn btn-default btn-raised',
                        'type' => 'submit'],
                ]);
                AjaxSubmitButton::end();
                ?>
            <?php } else { ?>
                <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
                <?= Yii::$app->controls->reset(); ?>
            <?php } ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
</div>

<?php ActiveForm::end(); ?>
