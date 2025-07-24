<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
?>
<?php
$readonly = $type == 'create' ? FALSE : TRUE;

$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('party_type', $model, $form, 'form-group', TRUE, $readonly, 'party_type', false); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'party_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'party_contact_no')->textInput(['class' => 'form-control check_mobile_length']) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'owner_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'owner_email')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'owner_contact_no')->textInput(['class' => 'form-control check_mobile_length']) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <?= $form->field($model, 'party_address')->textArea(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'owner_address')->textArea(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tblpartymaster-union_code,tblpartymaster-state_code', 'district_code', 'District'); ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblpartymaster-district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Sub District'); ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblpartymaster-sub_district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Village'); ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblpartymaster-village_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Hamlet'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bankdepended($model, $form, 'tblpartymaster-district_code', 'bank_code', 'Bank'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('branch', $model, $form, 'tblpartymaster-bank_code', '', 'Branch', 'branch_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'bank_account_no')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'ifsc')->textInput(['readonly' => true]) ?>        
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'beneficiary_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'pan_no')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'adhar_no')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'sap_vendor_code')->textInput() ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12">
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
    $('#tblpartymaster-branch_code').on('change',function(){
            var id = $('#tblpartymaster-branch_code').val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-branch/get-ifsc-code']) . "',
                        data: 'id='+id,
                        success: function(data) {
                                var obj1 = $.parseJSON(data);
                                $('#tblpartymaster-ifsc').val(obj1.code);
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
";
$this->registerJs($script, View::POS_END, 'bank-select');
?>
