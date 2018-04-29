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
    
    'options'=>['id'=>'bmc-form'],
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3 rtpl_validate">
        <?= $form->field($model, 'dcs_code')->textInput() ?>
    </div>
    <div class="col-sm-3 rtpl_validate">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type'); ?>
    </div>
    <div class="col-sm-3 rtpl_validate">
        <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, '', 'Milk Quality Type', false, 'milk_quality_type_code'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3 rtpl_validate">
        <?= Yii::$app->controls->date($model, $form, 'date_time_of_collection', '', date('Y-m-d'),false,false,true); ?>
    </div>
    <div class="col-sm-3 shift rtpl_validate">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift', true, false, 'shift'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1 rtpl_validate">
        <?= $form->field($model, 'fat')->textInput() ?>
    </div>
    <div class="col-sm-1 rtpl_validate">
        <?= $form->field($model, 'snf')->textInput() ?>
    </div>
    <div class="col-sm-1">
        <?= $form->field($model, 'rtpl')->textInput(['readOnly'=>true]) ?>
        <?= $form->field($model, 'rate_code')->hiddenInput(['readOnly'=>true])->label(false) ?>
    </div>
    <div class="col-sm-1">
        <?= $form->field($model, 'qty')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'amount')->textInput(['readOnly'=>true]) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= $form->field($model, 'remarks')->textarea() ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdownStatic('collection_type', $model, $form, 'form-group', $model->getAttributeLabel('collection_type'), false, 'collection_type', false); ?>
    </div>
    <div class="transporter">
        <div class="col-sm-3">
            <?= Yii::$app->dropdown->dropdown('transporter_code',$model, $form,'form-group col-sm-2 padding-right-5 padding-left-0',true,true,'transporter_code');  ?>
        </div>
        <div class='col-sm-3'>
            <?= Yii::$app->dropdown->vehicletransporter($model, $form, 'tblbmccollection-transporter_code', 'vehicle_code','Vehicle'); ?>
        </div>
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
    visible();
    $('#tblbmccollection-collection_type').change(function(){
        visible();
    });
    function visible(){
        var coll_type = $('#tblbmccollection-collection_type').val();
        if(coll_type == 1 || coll_type == ''){
            $('.transporter').hide();
            $('#tblbmccollection-transporter_code').prop('disabled', true); 
            $('#tblbmccollection-transporter_code').val(''); 
            $('#tblbmccollection-vehicle_code').val(''); 
        }else{
            $('.transporter').show();
            $('#tblbmccollection-transporter_code').prop('disabled', false); 
        }
    }

    amount();
    $('#tblbmccollection-rtpl').change(function(){
        amount();
    });
    $('#tblbmccollection-qty').change(function(){
        amount();
    });
    function amount(){
        var amount = 0;
        var rtpl = parseFloat($('#tblbmccollection-rtpl').val());
        var qty = parseFloat($('#tblbmccollection-qty').val());
        if(rtpl == '' || isNaN(rtpl)){
            rtpl = 0;
        }
        if(qty == '' || isNaN(qty)){
            qty = 0;
        }
        amount = rtpl * qty;
        $('#tblbmccollection-amount').val(amount);
    }

    $('#tblbmccollection-dcs_code').change(function(){
        var dcs = $(this).val();
        $.ajax({
            type: 'post',
            url:'" . Url::to(['validate-dcs']) . "',
            data: {'dcs_code':dcs},
            success: function(data) {                                        
                var obj = $.parseJSON(data);
                if (obj.status == 'success')
                {

                }else{
                    bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please enter valid Society Code</span></div></div>');
                    $('#tblbmccollection-dcs_code').focus();
                }
            },
            error:function(data){
		
	    }
	});
    });
    $('.rtpl_validate select').change(function(){
        rtpl();
    });
    $('.rtpl_validate input').change(function(){
        rtpl();
    });
    $('#tblbmccollection-date_time_of_collection').change(function(){
        $.pjax.reload('#bmc-collection',{data: $('#bmc-form').serialize(),timeout : false});
    });
    function rtpl(){
        var dcs = $('#tblbmccollection-dcs_code').val();
        var milk_type = $('#tblbmccollection-milk_type_code').val();
        var milk_quality_type = $('#tblbmccollection-milk_quality_type_code').val();
        var dt_date = $('#tblbmccollection-date_time_of_collection').val();
        var shift = $('#tblbmccollection-shift').val();
        var fat = $('#tblbmccollection-fat').val();
        var snf = $('#tblbmccollection-snf').val();
        if(dcs != '' && milk_type != '' && milk_quality_type != '' && dt_date!= '' && shift != '' && fat != '' && snf != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-rtpl']) . "',
                data: {'dcs_code':dcs,'milk_type':milk_type,'milk_quality_type':milk_quality_type,'dt_date':dt_date,'shift':shift,'fat':fat,'snf':snf},
                success: function(data) {   
                      var obj = $.parseJSON(data);
                      if (obj.status == 'success')
                      {
                            $('#tblbmccollection-rtpl').val(obj.data.list.rtpl);
                            $('#tblbmccollection-rate_code').val(obj.data.list.purchase_rate_code);
                      }else{
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>RTPL Not Available</span></div></div>');
                            $('#tblbmccollection-rtpl').val('');
                            $('#tblbmccollection-rate_code').val('');
                      }
                },
                error:function(data){

                }
            });
        }else{
            $('#tblbmccollection-rtpl').val('');
            $('#tblbmccollection-rate_code').val('');
        }
    }
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>