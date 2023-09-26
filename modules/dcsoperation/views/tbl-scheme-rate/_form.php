<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProduct */
/* @var $form yii\widgets\ActiveForm */

$readonly = $type == 'create' ? FALSE : TRUE;
$furl = Url::to(['/organisation/tbl-mcc-plant/get-union-mcc']);

?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<?= $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('from_shift'), false, 'from_shift'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('to_shift'), false, 'to_shift'); ?>
    </div>
<!--    <div class="col-sm-2">
        <?php //Yii::$app->dropdown->dropdown('rate_class', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('rate_class'), false, 'rate_class'); ?>
    </div> -->
    <div class="col-sm-2 number-validate ">
        <?= $form->field($model, 'rtpl')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('boolean_value', $model, $form, 'form-group', $model->getAttributeLabel('is_mcc_wise_rate'), false, 'is_mcc_wise_rate', false); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3 padding_left_0 padding_right_0 mcc_div">
        <h4 class="theme-box-heading mb10"><?= Yii::t('app', 'MCC') ?></h4>

        <div class="col-sm-12 bmc_codes-list height_100-284 overflow_auto">
            <?= Html::textInput('filter', '', ['class' => 'col-sm-12 margin_bottom_10', 'id' => 'bmc_codes', 'onkeyup' => 'checkBoxFilter(this)', 'placeholder' => "Search"]); ?>
            <div class="form-group">
                <div class="checkbox app-check-all-mcc app-check-list-padding">
                    <label class="route-text">
                        <?= Html::checkbox('checkall', false, ['id' => 'checkAllBmcList', 'class' => 'bmc-list-checkbox']) ?>
                        <label for="checkAllBmcList"><?= Yii::t('app', 'Check ALL') ?></label>
                    </label>
                </div>
            </div>
            <div class="col-sm-12 padding_left_0 padding_right_0" id="bmc_codes-list"></div>
        </div>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'description')->textArea(['rows' => 2]) ?>
    </div>
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
    $(document).ready(function () {
        visible();
    });
    $(document).on('change', '#tblschemerate-is_mcc_wise_rate', function() {  
          visible();
          loadMcc();
    });
    
    function visible(){
        var mcc_wise_rate = $('#tblschemerate-is_mcc_wise_rate').val();
        if(mcc_wise_rate == 1){
            $('.mcc_div').show();
            $('#tblschemerate-mcc_plant_code').val(''); 
             $('#tblschemerate-mcc_plant_code').val(''); 
            $('#tblschemerate-mcc_plant_code').trigger('select2:select');
            $('#tblschemerate-mcc_plant_code').trigger('change');
        }else{
           $('.mcc_div').hide();
            $('#tblschemerate-mcc_plant_code').val(''); 
            $('#tblschemerate-mcc_plant_code').trigger('select2:select');
            $('#tblschemerate-mcc_plant_code').trigger('change');
        }
    }
    
 function checkBoxFilter(val){
        var id = $(val).attr('id');
        var value = $(val).val();
        count = 0;
        $('#'+id+'-list div').each(function() {
            if ($(this).text().search(new RegExp(value, 'i')) < 0) {
                $(this).hide();
                $(this).find(':input').prop('disabled', true);
            } else {
                $(this).show();
                $(this).find(':input').prop('disabled', false);
                count++;
            }
        });
    }
    function loadMcc(){
        var unionCode = $('#tblschemerate-union_code').val();
        var rls = true;
        if(unionCode != null && unionCode != undefined && unionCode != '') {
            $.ajax({
                type: 'post',
                url: '{$furl}',
                data: {'union':unionCode,'RLS':rls},
                success: function(data) {
                    var obj1 = $.parseJSON(data);
                    $('#bmc_codes-list').html('');
                    $.each(obj1.data, function(index, value) {
                        $('#bmc_codes-list').append('<div class=\"col-sm-6 bmc-checklist checklist\" id=\"nd-'+index+'\"><div class=\"checkbox\"><input type=\"checkbox\" class=\"bmc-checkbox\" name=\"mcc_plant_code[]\" value=\"'+index+'\" id=\"'+index+'\"><label class=\"route-text\" for=\"'+index+'\">'+value+'</label></div></div>');
                    });
                },
                error:function(data){
                        //alert('Your data has not been submitted..Please try again');
                }
            });  
        } else {
            $('.bmc_dcs_list').html('')
        }
//        return false;
    }


     $(document).on('change', '#tblschemerate-union_code', function(e){
        e.preventDefault();
        loadMcc();
    });
    $('#checkAllBmcList').click(function (event) {
        $('.bmc-checkbox').prop('checked', $(this).is(':checked'));
    });
   
";
$this->registerJs($script, View::POS_END, 'scheme_rate');
?>
