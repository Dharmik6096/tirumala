<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
//$model->is_plant=$model->isNewRecord?0:$model->is_plant;
//$nameWarning = 0;
//$codeWarning = 0;
//if (!empty($_POST)) {
//    $nameWarning = $_POST['warning'];
//    $codeWarning = $_POST['code_warning'];
//}
$list = array('0' => 'No', '1' => 'Yes');
?>

<?php
$form = ActiveForm::begin([

            'options' => ['id' => 'bmc-form'],
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblmilkcollection-union_code', '', $model->getAttributeLabel('dcs_code')); ?>            
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblmilkcollection-dcs_code', '', $model->getAttributeLabel('member_code')); ?>
    </div>
    <div class="col-sm-2 rtpl_validate">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type'); ?>
    </div>
    <div class="col-sm-2 rtpl_validate">
        <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, '', 'Milk Quality Type', false, 'milk_quality_type_code'); ?>
    </div>
    <div class="col-sm-2 shift rtpl_validate">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift_code', true, false, 'shift_code'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1 rtpl_validate">
        <?= $form->field($model, 'fat')->textInput() ?>
    </div>
    <div class="col-sm-1 rtpl_validate">
        <?= $form->field($model, 'snf')->textInput() ?>
    </div>
    <div class="col-sm-1">
        <?= $form->field($model, 'clr')->textInput() ?>
    </div>
    <div class="col-sm-1">
        <?= $form->field($model, 'rtpl')->textInput(['readOnly' => true]) ?>
        <?= $form->field($model, 'purchase_rate_code')->hiddenInput(['readOnly' => true])->label(false) ?>
    </div>
    <div class="col-sm-1">
        <?= $form->field($model, 'water')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'no_of_can')->textInput() ?>
    </div>
    <div class="col-sm-1">
        <?= $form->field($model, 'qty')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'amount')->textInput(['readOnly' => true]) ?>
    </div>
    <div class="clearfix"></div>
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
    amount();
    $('#tblmilkcollection-rtpl').change(function(){
        amount();
    });
    $('#tblmilkcollection-qty').change(function(){
        amount();
    });
    function amount(){
        var amount = 0;
        var rtpl = parseFloat($('#tblmilkcollection-rtpl').val());
        var qty = parseFloat($('#tblmilkcollection-qty').val());
        if(rtpl == '' || isNaN(rtpl)){
            rtpl = 0;
        }
        if(qty == '' || isNaN(qty)){
            qty = 0;
        }
        amount = rtpl * qty;
        $('#tblmilkcollection-amount').val(amount);
    }

    $('.rtpl_validate select').change(function(){
        rtpl();
    });
    $('.rtpl_validate input').change(function(){
        rtpl();
    });
    function rtpl(){
        var dcs = $('#tblmilkcollection-dcs_code').val();
        var milk_type = $('#tblmilkcollection-milk_type_code').val();
        var milk_quality_type = $('#tblmilkcollection-milk_quality_type_code').val();
        var dt_date = '".date('Y-m-d H:i:s')."';
        var shift = $('#tblmilkcollection-shift_code').val();
        var fat = $('#tblmilkcollection-fat').val();
        var snf = $('#tblmilkcollection-snf').val();
        if(dcs != '' && milk_type != '' && milk_quality_type != '' && dt_date!= '' && shift != '' && fat != '' && snf != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-rtpl']) . "',
                data: {'dcs_code':dcs,'milk_type':milk_type,'milk_quality_type':milk_quality_type,'dt_date':dt_date,'shift_code':shift,'fat':fat,'snf':snf},
                success: function(data) {   
                      var obj = $.parseJSON(data);
                      if (obj.status == 'success')
                      {
                            $('#tblmilkcollection-rtpl').val(obj.data.list.rtpl);
                            $('#tblmilkcollection-rate_code').val(obj.data.list.purchase_rate_code);
                      }else{
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>RTPL Not Available</span></div></div>');
                            $('#tblmilkcollection-rtpl').val('');
                            $('#tblmilkcollection-rate_code').val('');
                      }
                },
                error:function(data){

                }
            });
        }else{
            $('#tblmilkcollection-rtpl').val('');
            $('#tblmilkcollection-rate_code').val('');
        }
    }
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>