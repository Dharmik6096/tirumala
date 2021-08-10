<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMember */
/* @var $form yii\widgets\ActiveForm */

$readonly = $type == 'create' ? FALSE : TRUE;
$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
$bankWarning = !empty($_POST['bank_ac_warning']) ? $_POST['bank_ac_warning'] : 0;
?>

<?php
$form = ActiveForm::begin([
            'options' => [],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
$disable_ifsc = !empty($model->ifsc) && !empty($model->bank_code) ? true : false;
$selected = $model->union_code;
$union_code = count(explode(',', Yii::$app->session->get('Unions'))) == 1 ? Yii::$app->session->get('Unions') : '';
$model->union_code = !empty($selected) ? $selected : $union_code;
if ($model->isNewRecord) {
    $type = 'create';
    $disabled = false;
} else {
    $type = 'edit';
    $disabled = true;
}
$config = (count(explode(',', Yii::$app->session->get('Unions'))) == 1 && !empty(Yii::$app->session->get('unionConfig')[Yii::$app->session->get('Unions')]['member_with_class'])) ? Yii::$app->session->get('unionConfig')[Yii::$app->session->get('Unions')]['member_with_class'] : 0;
?>
<?php echo $form->errorSummary($model); ?>
<?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>
<?= Html::hiddenInput('bank_ac_warning', $bankWarning, ['id' => 'bank_ac_warning']); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-6 padding_10_0 theme-box theme_border_right">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'Member Details') ?></h4>
        </div>
        <div class="col-sm-4" id="union">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblmember-union_code', '', 'Society', '', $readonly); ?>
        </div>
        <?php //Html::activeHiddenInput($model, 'district_code');  ?>
        <?php
        $keyPattern = Yii::$app->general->getKeyPattern('tbl_member');
        if (!empty($keyPattern)) {
            ?>
            <?php if (!$readonly && $keyPattern['ex_code_auto'] == 0) { ?>
                <div class="col-sm-4 number-validate">  
                    <?= $form->field($model, 'ex_member_code')->textInput(['readonly' => $readonly]) ?>
                </div>
            <?php } ?>
            <?php if (!$readonly && $keyPattern['ref_code_type'] == 2) { ?>
                <div class="col-sm-4 number-validate">  
                    <?= $form->field($model, 'ref_code')->textInput(['readonly' => $readonly]) ?>
                </div>
            <?php } ?>
        <?php } ?>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('member-type', $model, $form, '', $model->getAttributeLabel('member_type_code')); ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'member_name')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'local_name')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'father_name')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'local_father_name')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'surname')->textInput() ?>
        </div>
        <!--        <div class="col-sm-4">
        <?php // $form->field($model, 'local_surname')->textInput() ?>
                </div>-->
        <div class="col-sm-4">
            <?= $form->field($model, 'nominee_name')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'local_nominee_name')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('relation', $model, $form, '', 'Relation With Nominee'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->controls->date($model, $form, 'dob'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('blood-group', $model, $form, '', 'Blood Group'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('gender', $model, $form, '', 'Gender'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('qualification', $model, $form, '', 'Qualification'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('caste-category', $model, $form, '', 'Caste/Category'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdown('religion', $model, $form, '', 'Religion'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->controls->date($model, $form, 'registration_date'); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->dropdownStatic('member_class', $model, $form, 'form-group', $model->getAttributeLabel('member_class'), false, 'member_class', false); ?>
        </div>
        <!--    <div class="col-sm-4">
                <? //$form->field($model, 'land_class')->textInput() ?>
            </div>-->
        <div class="col-sm-4">
            <?= $form->field($model, 'total_land')->textInput() ?>
        </div>
        <div class="col-sm-4 number-validate">
            <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => 10]) ?>   
        </div>
        <?php if ($config == 1) { ?>
            <div class="col-sm-4">
                <?= Yii::$app->dropdown->dropdownStatic('rate_class', $model, $form, 'form-group', $model->getAttributeLabel('rate_class'), false, 'rate_class', false); ?>
            </div>
        <?php } ?>
    </div>
    <!-- <div class="clearfix"></div>
    <div class="col-sm-12">
        <p class="form-subtitle">Animal Details</p>
        <hr class="hr10">
    </div>
    <div class="col-sm-4">
    <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type', false, 'animal_type_code'); ?>
    </div>
    <div class="col-sm-4">
    <?= $form->field($model, 'no_of_buffalo')->textInput() ?>
    </div>
    <div class="col-sm-4">
    <?= $form->field($model, 'no_of_cow_cross')->textInput() ?>
    </div>
    <div class="col-sm-4">
    <?= $form->field($model, 'no_of_cow_ind')->textInput() ?>
    </div>
    <div class="col-sm-4">
    <?= $form->field($model, 'total_animals')->textInput(['readonly' => 'disable']) ?>
    </div> -->

    <div class="col-sm-6 col-md-6 padding_left_0 padding_right_0 clearfix">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Contact Details</h4>
        </div>

        <div class="col-sm-4 padding_left_right_0">
            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($model, 'address')->textArea(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-12">
                    <?= Yii::$app->controls->local_textarea($model, $form, 'local_address'); ?>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
        </div>
        <div class="col-sm-4">
            <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tblmember-union_code,tblmember-state_code', 'district_code', 'District'); ?>
        </div>
        <div class="col-sm-4">
            <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblmember-district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Sub District'); ?>
        </div>
        <div class="col-sm-4">
            <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblmember-sub_district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Village'); ?>
        </div>
        <div class="col-sm-4">
            <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblmember-village_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Hamlet'); ?>
        </div>
        <div class="col-sm-4">
            <!--<? = $form->field($model, 'pincode')->textInput() ?>-->
            <?= $form->field($model, 'pincode')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'mobile_no')->textInput(['class' => 'form-control check_mobile_length']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'email')->textInput() ?>
        </div>

        <div class="col-md-12 padding_10_0 theme-box ">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading">Animal Details</h4>
            </div>
            <div class="col-sm-4">
                <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type', false, 'animal_type_code'); ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'no_of_buffalo')->textInput() ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'no_of_cow_cross')->textInput() ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'no_of_cow_ind')->textInput() ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'total_animals')->textInput(['readonly' => 'disable']) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Bank Details</h4>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->bankdepended($model, $form, 'tblmember-district_code', 'bank_code', 'Bank'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->depend_dropdown('branch', $model, $form, 'tblmember-bank_code', '', 'Branch', 'branch_code'); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'bank_account_no')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <!--<? = $form->field($model, 'ifsc')->textInput(['readonly' => $disable_ifsc]) ?>-->
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
            <?= $form->field($model, 'voter_id')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'annual_income')->textInput() ?>
        </div>
        <!--    <div class="col-sm-3">
                <? = $form->field($model, 'payment_mode')->textInput() ?>
            </div>-->
        <div class="clearfix"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
            <?php //Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
            ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    

    $('#tblmember-bank_account_no').on('change', function(){
        $('#bank_ac_warning').val(0);
    });
    $('#tblmember-branch_code').on('change', function(){
        $('#bank_ac_warning').val(0);
    });
    $('#tblmember-ifsc').on('change', function(){
        $('#bank_ac_warning').val(0);
    });
    $('#tblmember-dcs_code').on('change',function(){
        var id = $(this).val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/dcsoperation/tbl-member/district-code']) . "',
                        data: 'dcs_code='+id,
                        success: function(data) {
                                if(data){
                                    $('#tblmember-district_code').val(data);
                                    $('#tblmember-district_code').trigger('select2:select');
                                    $('#tblmember-district_code').trigger('change');
                                }
                               
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
    
    $('#tblmember-no_of_buffalo, #tblmember-no_of_cow_cross, #tblmember-no_of_cow_ind').on('change',function(){
            var no_of_buffalo = document.getElementById('tblmember-no_of_buffalo').value;
            var no_of_cow_cross = document.getElementById('tblmember-no_of_cow_cross').value;
            var no_of_cow_ind = document.getElementById('tblmember-no_of_cow_ind').value;
            if(no_of_buffalo == '') {no_of_buffalo = 0}
            if(no_of_cow_cross == '') {no_of_cow_cross = 0}
            if(no_of_cow_ind == '') {no_of_cow_ind = 0}
            var result = parseInt(no_of_buffalo) + parseInt(no_of_cow_cross) + parseInt(no_of_cow_ind);
            if (!isNaN(result)) {
                document.getElementById('tblmember-total_animals').value = result;
            }
            $('#tblmember-total_animals').prop('readonly', true);
    });
	var changeAc = false;

    $('#tblmember-bank_code').on('change',function(){
        var existBank = '" . $model->bank_code . "';
        var newBank = $(this).val();
        if(newBank == null || newBank == '' || newBank == undefined) {
            newBank = existBank;
        }
        $('#tblmember-ifsc').val('');
        if(existBank != newBank || changeAc){
            $('#tblmember-bank_account_no').val('');
            changeAc = true;
        }
    });


    $('#tblmember-branch_code').on('change',function(){
            
            var id = $(this).val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-branch/get-ifsc-code']) . "',
                        data: 'id='+id,
                        success: function(data) {
                                var obj1 = $.parseJSON(data);
                                $('#tblmember-ifsc').val(obj1.code);
//                                if(obj1.code!='')
//                                    $('#tblmember-ifsc').prop('readonly', true);
//                                else
//                                    $('#tblmember-ifsc').prop('readonly', false);
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
    
    $('#tblmember-member_name').on('change',function(){
       var name = $('#tblmember-member_name').val();
       $('#tblmember-beneficiary_name').val(name);
    });
    
    $('#tblmember-pan_no').on('input', function(evt) {
        $(this).val(function(_, val) {
        return val.toUpperCase();
    });
   });
";
$this->registerJs($script, View::POS_END, 'union');
