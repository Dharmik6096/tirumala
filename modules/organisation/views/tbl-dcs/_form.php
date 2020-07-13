<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$milkType = $model->getMilkTypes();
$nameWarning = 0;
$codeWarning = 0;
$readonly = $type == 'create' ? FALSE : TRUE;
if (!empty($_POST) && !empty($_POST['warning']) && !empty($_POST['code_warning'])) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
if ($model->isNewRecord) {
    $disabled = false;
} else {
    $disabled = true;
}
$model->destination_type = 0;
$model->is_bmc = !empty($model->is_bmc) ? $model->is_bmc : 0;
$bmcDisable = $model->is_bmc == 1 ? 'disabled' : '';
$model->destination_code = 0;
//$model->route_code = 0;
$summary_model = $type == 'create' ? [$model, $bankDetails, $contactDetails] : $model;
$address = explode(",", $model->address);
$model->street1 = $address[0];
if (isset($address[1])) {
    $model->street2 = $address[1];
}

$vendor = ['EIPL' => 'EIPL', 'BIPL' => 'BIPL'];
($type == 'edit') ? $disabled = true : $disabled = false;
//var_dump($bmc);exit;
//$disable = !empty($model->bmc_code) ? TRUE : FALSE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($summary_model); ?>
<?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>
<div class="row">
    <div class="col-sm-3" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <?= Html::activeHiddenInput($model, 'is_bmc') ?>
    <?= Html::activeHiddenInput($model, 'destination_type') ?>
    <?= Html::activeHiddenInput($model, 'destination_code') ?>
    <?= Html::activeHiddenInput($model, 'route_code') ?>

    <div class="col-sm-3 <?= $bmcDisable ?>">
        <?= Yii::$app->dropdown->bmcDropdown($model, $form, 'tbldcs-union_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), FALSE, $readonly); ?>
    </div>
    <?php
    $keyPattern = Yii::$app->general->getKeyPattern('tbl_dcs');
    if (!empty($keyPattern)) {
        ?>
        <?php if ($readonly || $keyPattern['ex_code_auto'] == 0) { ?>
            <div class="col-sm-3 number-validate">  
                <?= $form->field($model, 'dcs_code_ex')->textInput() ?>
            </div>
        <?php } ?>
        <?php if ($readonly || $keyPattern['ref_code_type'] == 2) { ?>
            <div class="col-sm-3 number-validate">  
                <?= $form->field($model, 'ref_code')->textInput() ?>
            </div>
        <?php } ?>
    <?php } ?>
    <div class="col-sm-3">
        <?= $form->field($model, 'dcs_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= $form->field($model, 'milk_type_code')->listBox($milkType['value'], ['multiple' => 'multiple', 'size' => '10', 'options' => $milkType['selected']]); ?>
    </div>
    <?php //Yii::$app->dropdown->ismilk($model, $form, 'milk_type_code', 'Milk Type');    ?>
    <div class="col-sm-3">
        <?= $form->field($model, 'dcs_short_name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-sm-3">
        <?= Yii::$app->controls->local($model, $form, 'local_short_name'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'vendor')->dropdownList($vendor, ['prompt' => 'Select Vendor', 'disabled' => (!empty($model->vendor) && $model->vendor != 'NA' && $readonly)]); ?>
    </div>
    <!--<div class="col-sm-3">-->
    <?= Yii::$app->dropdown->dropdownStatic('dpu_type', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('dpu_type'), false); ?>
    <!--</div>-->
    <?php if ($type == 'create') { ?>
        <div class="col-sm-3">
            <?= Yii::$app->dropdown->memberRateChart($model, $form, 'tbldcs-union_code', 'rate_chart_member', $model->getAttributeLabel('rate_chart_member')); ?>
        </div>
    <?php } ?>
    <div class="clearfix"></div>
    <!--    <div class="col-sm-3">
            <? = $form->field($model, 'address')->textArea(['maxlength' => true]) ?>
        </div>-->
    <div class="col-sm-3">
        <div class="row">
            <div class="col-sm-12">
                <?= $form->field($model, 'street1')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-12">
                <?= $form->field($model, 'street2')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local_textarea($model, $form, 'local_address'); ?>
    </div>
    <?php
    //Yii::$app->dropdown->state($model, $form, 'state_code', 'State');
    ?>
    <?php // if($type=='create') { ?>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
    </div>
    <div class="col-sm-3" id="district_section">
        <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tbldcs-union_code,tbldcs-state_code', 'district_code', 'District', FALSE); ?>
    </div>
    <!--    <div class="col-sm-3">
    <?php //Yii::$app->dropdown->district($model, $form, 'tbldcs-state_code', 'district_code', 'District');  ?>
        </div>-->
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tbldcs-district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Sub District', ''); ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tbldcs-sub_district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Village', ''); ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('block_code', $model, $form, 'tbldcs-sub_district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Block'); ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tbldcs-village_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Hamlet'); ?>
    </div>
    <?php // }  ?>
    <div class="col-sm-3">
        <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3  mt25">
        <?= $form->field($model, 'is_registered', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'registration_code')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'registration_date'); ?>
    </div>
    <?php
    /* echo $form->field($model, 'registration_date', ['options' => ['class' => 'form-group col-sm-2']])->widget(DatePicker::className(), [
      'model' => $model,
      'attribute' => 'registration_date',
      'dateFormat' => 'dd-MM-yyyy',
      'clientOptions' => [ 'readonly' => true, 'value' => date('Y-m-d')],
      'options' => ['class' => 'form-control',]
      ]); */
    ?>
    <?php //Yii::$app->dropdown->depend_dropdown('route_code',$model, $form, 'tbldcs-union_code','form-group col-sm-2 padding-right-5 padding-left-0','Route');  ?>
    <!--    <div class="col-sm-3">
    <?php //$form->field($model, 'tin_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
    <?php //$form->field($model, 'service_tax')->textInput(['maxlength' => true]) ?>
        </div>-->
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('dcs_type_code', $model, $form, 'form-group col-sm-3', Yii::t('app', 'Society Type')); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('organisation_type', $model, $form, 'form-group col-sm-3', 'Organisation Type'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('scheme_type', $model, $form, 'form-group col-sm-3', 'Scheme Type'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'effective_date', 'form-group col-sm-2'); ?>
    </div>
    <div class="clearfix"></div>
    <?php
    /* echo $form->field($model, 'effective_date', ['options' => ['class' => 'form-group col-sm-2']])->widget(DatePicker::className(), [
      'model' => $model,
      'attribute' => 'effective_date',
      'dateFormat' => 'dd-MM-yyyy',
      'clientOptions' => [ 'readonly' => true, 'value' => date('Y-m-d')],
      'options' => ['class' => 'form-control',]
      ]); */
    ?>
    <div class="col-sm-3">
        <?= $form->field($model, 'secretory_info')->textarea(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'pan_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'gst_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'fssi')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->valid_date($model, $form, 'valid_from'); ?>
    </div>
    <div class='pull-left col-sm-6'>
        <?= Yii::t('app', 'Allow multiple collection entry for shift') ?><br/>
        <?= $form->field($model, 'same_milk_type', ['options' => ['class' => 'form-group col-sm-3 padding-left-0'], 'checkboxTemplate' => "<div class='checkbox' >{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        <?= $form->field($model, 'diff_milk_type', ['options' => ['class' => 'form-group col-sm-4'], 'checkboxTemplate' => '<div class="checkbox" >{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}'])->checkbox(); ?>
    </div>

    <div class="clearfix"></div>
    <?php if ($type == 'create') { ?>
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
        <?=
        $this->render('../../../details/views/tbl-bank-details/_form', [
            'model' => $bankDetails,
            'form' => $form,
            'dist_field' => 'tbldcs-district_code'
        ])
        ?>
    <?php } ?>

    <!--<div class="col-sm-3">-->
    <?= Yii::$app->dropdown->dropdownStatic('is_dispatch_mandate', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('is_dispatch_mandate'), false); ?>
    <!--</div>-->
    <div class="col-sm-3 mt25">
        <?= $form->field($model, 'allow_multi_family_member', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <!--<div class="col-sm-3">-->
    <?php // $form->field($model, 'is_dispatch_mandate', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    <!--</div>-->
    <div class="col-sm-2 mt25">
        <?= $form->field($model, 'is_weight_manual', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="col-sm-2 mt25">
        <?= $form->field($model, 'is_quality_manual', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="col-sm-2 mt25">
        <?= $form->field($model, 'credit_sale_allow', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>

    <?php // if ($type == 'create') { ?>
    <!--        <div class="col-sm-3">
    <?= Yii::$app->controls->active($model, $form); ?>
            </div>-->
    <?php // } ?>
    <?= Html::hiddenInput('bmc', '', ['id' => 'bmc-data']); ?>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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
    //$('#district_section').hide();
    $('#tbldcs-bank_code').on('change',function(){
    $('#tbldcs-branch_code,#tblbankdetails-bank_account_no,#tblbankdetails-ifsc').trigger('change');
    });
   $('#tbldcs-branch_code').on('change',function(){
            var id = $('#tbldcs-branch_code').val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-branch/get-ifsc-code']) . "',
                        data: 'id='+id,
                        success: function(data) {
                                var obj1 = $.parseJSON(data);
                                $('#tbldcs-ifsc').val(obj1.code);
                                if(obj1.code!='')
                                    $('#tbldcs-ifsc').prop('readonly', true);
                                else
                                    $('#tbldcs-ifsc').prop('readonly', false);
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
";
$this->registerJs($script, View::POS_END, 'union-select');

$script = "var delay=2000;";
$this->registerJs($script, View::POS_HEAD, 'time-loader');
