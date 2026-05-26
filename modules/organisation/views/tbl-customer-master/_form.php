<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
$nameWarning = 0;
$nameWarning = !empty($_POST['warning']) ? $_POST['warning'] : 0;
?>

<?php
$form = ActiveForm::begin([
            'options' => [],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<?= Html::hiddenInput('warning', $nameWarning, ['id' => 'warning']); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-6 padding_10_0 theme-box theme_border_right">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'Customer Details') ?></h4>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblcustomermaster-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, '', $readonly); ?>  
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblcustomermaster-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, '', $readonly); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblcustomermaster-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', '', $readonly); ?>
        </div>
        <div class="col-sm-4 DCS">
            <?= Yii::$app->dropdown->all_routes($model, $form, 'tblcustomermaster-plant_code,tblcustomermaster-mcc_plant_code,tblcustomermaster-bmc_code', 'route_code', $model->getAttributeLabel('route_code'), FALSE); ?>
        </div>
        <!-- <div class="clearfix"></div> -->
        <div class="col-sm-4 ">
            <?= Yii::$app->dropdown->dropdown('customer_type', $model, $form, 'form-group col-sm-4', $model->getAttributeLabel('customer_type'), $readonly); ?>
        </div>
        <?php
        $keyPattern = Yii::$app->general->getKeyPattern('tbl_customer_master');
        if (!empty($keyPattern)) {
            ?>
            <?php if ($readonly || $keyPattern['ex_code_auto'] == 0) { ?>
                <div class="col-sm-1 "> 
                    <?= $form->field($model, 'prefix')->textInput(['readOnly' => true]) ?>
                </div>
                <div class="col-sm-3 number-validate"> 
                    <?= $form->field($model, 'customer_code_ex')->textInput() ?>
                </div>
            <?php } ?>
            <?php if ($readonly || $keyPattern['ref_code_type'] == 2) { ?>
                <div class="col-sm-4 number-validate">  
                    <?= $form->field($model, 'ref_code')->textInput() ?>
                </div>
            <?php } ?>
        <?php } ?>
        <div class="col-sm-4">
            <?= $form->field($model, 'customer_name')->textInput() ?>
        </div>

        <!-- <div class="col-sm-4 ">
        <?= Yii::$app->dropdown->dropdown('customer_type', $model, $form, 'form-group col-sm-4', $model->getAttributeLabel('customer_type'), $readonly); ?>
        </div>
        <div class="col-sm-4">
        <?= $form->field($model, 'customer_code_ex')->textInput() ?>
        </div>
        <div class="col-sm-4">
        <?= $form->field($model, 'customer_name')->textInput() ?>
        </div> -->

        <div class="col-sm-4">
            <?= $form->field($model, 'local_name')->textInput() ?>
        </div>
        <div class="col-sm-4 hidden-for-specific-client">
            <?= $form->field($model, 'gst_no')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'aadhaar_no')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'sap_vendor_code')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdownStatic('collection', $model, $form, '', $model->getAttributeLabel('x_col2'), false, 'x_col2', false); ?>    
        </div>
        <div class="col-sm-4 d-none-for-specific-client">
            <?= Yii::$app->dropdown->dropdownStatic('customer_category', $model, $form, '', $model->getAttributeLabel('customer_category'), false, 'customer_category', false); ?>    
        </div>
        <div class="col-sm-4 number-validate">  
            <?= $form->field($model, 'ts_code_m')->textInput() ?>
        </div>
        <div class="col-sm-4 number-validate">  
            <?= $form->field($model, 'ts_code_e')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type', false, 'animal_type_code'); ?>
        </div>
        <div class="col-sm-4">  
            <?= $form->field($model, 'distance_from_mcc')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'pan_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2 mt15">
            <?= $form->field($model, 'is_weight_manual', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        </div>
        <div class="col-sm-2 mt15">
            <?= $form->field($model, 'is_quality_manual', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        </div>
        <div class='pull-left col-sm-8'>
            <?= Yii::t('app', 'Allow multiple collection entry for shift') ?><br/>
            <?= $form->field($model, 'same_milk_type', ['options' => ['class' => 'form-group col-sm-4 padding-left-0'], 'checkboxTemplate' => "<div class='checkbox' >{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
            <?= $form->field($model, 'diff_milk_type', ['options' => ['class' => 'form-group col-sm-4'], 'checkboxTemplate' => '<div class="checkbox" >{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}'])->checkbox(); ?>
        </div>
    </div>
    <div class="col-md-6 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Address Details</h4>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'address')->textArea(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->controls->local_textarea($model, $form, 'local_address'); ?>
        </div>

        <div class="col-sm-4">
            <?= Yii::$app->dropdown->state($model, $form, 'state_code', $model->getAttributeLabel('state_code'), FALSE); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->depend_dropdown('district_code', $model, $form, 'tblcustomermaster-state_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('district_code'), 'district_code', FALSE); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblcustomermaster-district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('sub_district_code'), 'sub_district_code', FALSE); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblcustomermaster-sub_district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('village_code'), 'village_code', FALSE); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblcustomermaster-village_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('hamlet_code'), 'hamlet_code', FALSE); ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php if ($type == 'create') { ?>
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Contact Details</h4>
        </div>
        <?=
        $this->render('../../../details/views/tbl-contact-details/_form', [
            'model' => $contactDetails,
            'form' => $form
        ])
        ?>

        <div class="clearfix"></div>

        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Bank Details</h4>
        </div>
        <?=
        $this->render('../../../details/views/tbl-bank-details/_form', [
            'model' => $bankDetails,
            'form' => $form,
            'dist_field' => 'tblcustomermaster-district_code'
        ])
        ?>
    <?php } ?>

</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>



<?php
$script = "
    $('#tblbankdetails-bank_account_no').on('change', function(){
        $('#warning').val(0);
    });
    $('#tblbankdetails-branch_code').on('change', function(){
        $('#warning').val(0);
    });
    $('#tblbankdetails-ifsc').on('change', function(){
        $('#warning').val(0);
    });
    $('#tblcustomermaster-customer_type').on('change', function(){
    $('#tblcustomermaster-prefix').val('');
        PreffixValue();
    });
    $(document).on('change', '#tblcustomermaster-union_code', function() {  
    $('#tblcustomermaster-prefix').val('');
        PreffixValue();
    });
//    $('#tblcustomermaster-customer_type').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
//       console.log('tset');
//    });
    function PreffixValue(){
        var union = $('#tblcustomermaster-union_code').val();
        var type = $('#tblcustomermaster-customer_type').val();
            if(union !='' && type !=''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['excode-prefix']) . "',
                    data: {'union_code':union,'type':type},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                        console.log(obj.data);
                          $('#tblcustomermaster-prefix').val(obj.data);
                        }
                    },
                    error:function(data){

                    }
                });
            }
    };
    $('#tblcustomermaster-pan_no').on('input', function(evt) {
        $(this).val(function(_, val) {
        return val.toUpperCase();
    });
   });


";
$this->registerJs($script, View::POS_END, 'customer_mastercreate');
?>