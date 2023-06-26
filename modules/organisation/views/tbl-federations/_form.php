<?php

use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
$url= ($model->isNewRecord) ? '' : Url::to(['../../organisation/tbl-federations/view','id'=>$model->federation_code]);
$checkChild = ($model->getCheckUnionExist()) ? ' disabled' : '';

$readonly=$type=='create'?FALSE:TRUE;
$summary_model=$type=='create'?[$model,$bankDetails,$contactDetails]:$model;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            //'enableAjaxValidation' => true,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($summary_model); ?>
<div class="row">
    <div class="col-sm-3">
        <?= $form->field($model, 'federation_code_ex')->textInput(['maxlength' => true, 'readOnly' => $readonly]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'federation_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local($model, $form); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'federation_short_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'registration_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'registration_date'); ?>
    </div>
    <div class="col-sm-3 <?= $checkChild; ?>">
        <?= Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
    </div>
    <div class="col-sm-3 <?php// echo $checkChild; ?>">
        <?= Yii::$app->dropdown->district($model, $form, 'tblfederations-state_code', 'district_code', 'District',FALSE,$readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblfederations-district_code', '', 'Sub District', '',$readonly); ?>
    </div>
    <div class="col-sm-3 ">
        <?= Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblfederations-sub_district_code', '', 'Village','',$readonly); ?>
    </div>
    <div class="col-sm-3 ">
        <?= Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblfederations-village_code', '', 'Hamlet'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= $form->field($model, 'address')->textarea() ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local_textarea($model, $form, 'local_address'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'fax_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="clearfix"></div>
   
    <div class="clearfix"></div>
    <?php if($type=='create') { ?>
    <div class="col-sm-12">
        <p class="form-subtitle">Contact Details</p>
        <hr class="hr10">
    </div>
      <?=
        $this->render('../../../details/views/tbl-contact-details/_form', [
            'model' => $contactDetails,
            'form' => $form
        ])
        ?>
    
    <div class="clearfix"></div>
   
    <div class="col-sm-12">
        <p class="form-subtitle">Bank Details</p>
        <hr class="hr10">
    </div>
    <?php //Yii::$app->dropdown->dropdown('bank', $model, $form, 'form-group col-sm-3','Bank');   ?>
   <?=
        $this->render('../../../details/views/tbl-bank-details/_form', [
            'model' => $bankDetails,
            'form' => $form,
            'dist_field'=>'tblfederations-district_code'
        ])
        ?>
    <?php } ?>
    
<!--    <div class="col-sm-3">
        <? = $form->field($model, 'contact_person_email')->textInput(['maxlength' => true]) ?>
    </div>-->
    <div class="col-sm-3">
        <?= $form->field($model, 'contact_person_pan_no')->textInput(['maxlength' => true]) ?>
    </div>
<!--    <div class="col-sm-3">
        <? = $form->field($model, 'upi_no')->textInput(['maxlength' => true]) ?>
    </div>-->
    <div class="col-sm-3 mt25">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?php if(Yii::$app->general->checkAccess('/organisation/tbl-federation/index')) { ?>
                <?= Yii::$app->controls->cancel($model); ?>
            <?php } else { ?> 
                <?= Yii::$app->controls->cancel($model,$url); ?>
            <?php } ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    $('#tblfederations-branch_code').on('change',function(){
            var id = $('#tblfederations-branch_code').val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-branch/get-ifsc-code']) . "',
                        data: 'id='+id,
                        success: function(data) {
                                var obj1 = $.parseJSON(data);
                                $('#tblfederations-ifsc').val(obj1.code);
                                if(obj1.code!='')
                                    $('#tblfederations-ifsc').prop('readonly', true);
                                else
                                    $('#tblfederations-ifsc').prop('readonly', false);
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
";
$this->registerJs($script, View::POS_END, 'branch-code');
$script = "var delay=2000;";
$this->registerJs($script, View::POS_HEAD, 'time-loader');
?>