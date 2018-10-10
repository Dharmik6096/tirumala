<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;

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

            'options' => ['id' => 'milk-coll-temp-form'],
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
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmilkcollectiontemp-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div> 

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmilkcollectiontemp-plant_code', 'mcc_code', $model->getAttributeLabel('mcc_code')); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmilkcollectiontemp-mcc_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmilkcollectiontemp-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code')); ?>         
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'date_time_of_collection', 'form-group col-sm-2'); ?>
    </div>
    <div class="col-sm-2 shift rtpl_validate">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'shift_code', true, false, 'shift'); ?>
    </div>  
    <div class="clearfix"></div>
    <div id="coll_form">
        <div class="col-sm-2">
            <?= $form->field($model, 'member_code')->textInput() ?>
            <?php // Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblmilkcollection-dcs_code', '', $model->getAttributeLabel('member_code')); ?>
        </div>

        <div class="col-sm-2 rtpl_validate">
            <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type'); ?>
        </div>
        <div class="col-sm-2 rtpl_validate">
            <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, '', 'Milk Quality Type', false, 'milk_quality_type_code'); ?>
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
            <?= $form->field($model, 'rate_code')->hiddenInput(['readOnly' => true])->label(false) ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($model, 'qty')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'amount')->textInput(['readOnly' => true]) ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?php
            AjaxSubmitButton::begin([

                'label' => Yii::t('app', 'Add'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to(['create']),
                    'beforeSend' => new JsExpression("function(data){
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                    'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $(\'#loadercontent\').hide();
                                                                $(\'#pageloader\').hide();
                                                                if (data.status == "success"){ 
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $("tr.added_row").remove();
                                                                    var row = "";
                                                                    var count = 1;
                                                                   
                                                                    $.each(data.temp_collection_data, function(key, val) {
                                                                        row += "<tr class=\'added_row\'>";
                                                                        row += "<td>"+count+"</td>";
                                                                        $.each(val, function(keys, vals) {
                                                                            if(keys != "milk_receipt_code"){
                                                                                row += "<td>"+vals+"</td>";
                                                                            }else{
                                                                                row += "<td><a href=\"javascript:void(0)\" class=\'edit\' onClick=\'editReceipt(\""+vals+"\")\' id=\""+vals+"\" title=\"Edit\"><span class=\"glyphicon glyphicon-pencil\"></span></a></td>";
                                                                            }
                                                                        });
                                                                        row += "</tr>";
                                                                        $("#receipt_form table tbody").append(row);
                                                                        row = "";
                                                                        count = count + 1;
                                                                    });
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    reloadGrid();
                                                                    $("#coll_form input").val("");
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
                                                                }else{
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data, function(key, val) {
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                    });
                                                                    $(".error-summary").show();
                                                                }
                                                 }'),
                ],
                'options' => ['class' => 'btn btn-default btn-raised',
                    'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
            ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
<h5 class="panel-heading"><?= Yii::t('app', 'Milk Collection Temp Details') ?></h5>
<?php
$attribute = [
    ['header' => 'Member Code', 'attribute' => 'member_code', 'value' => function($model) {
            return substr($model->member_code, -4);
        }, 'filter' => false],
    ['attribute' => 'member_code', 'label' => 'Member Name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'filter' => false],
    ['attribute' => 'name', 'filter' => false, 'visible' => false],
    ['attribute' => 'milk_type_code', 'value' => 'milkTypeCode.animal_type_name', 'filter' => false],
    ['attribute' => 'fat', 'filter' => false],
    ['attribute' => 'snf', 'filter' => false],
    ['attribute' => 'qty', 'filter' => false],
    ['attribute' => 'rtpl', 'filter' => false],
    ['attribute' => 'amount', 'filter' => false],
    ['attribute' => 'date_time_of_collection', 'filter' => false, 'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }],
    ['attribute' => 'shift', 'value' => 'shiftCode.shift', 'filter' => false],
];

$grid_option = [
    'id' => 'milk-coll-temp-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['milk-coll-temp-grid']);
?>
<?php
$script = "
    amount();
    $('#tblmilkcollectiontemp-rtpl').change(function(){
        amount();
    });
    $('#tblmilkcollectiontemp-qty').change(function(){
        amount();
    });
    function amount(){
        var amount = 0;
        var rtpl = parseFloat($('#tblmilkcollectiontemp-rtpl').val());
        var qty = parseFloat($('#tblmilkcollectiontemp-qty').val());
        if(rtpl == '' || isNaN(rtpl)){
            rtpl = 0;
        }
        if(qty == '' || isNaN(qty)){
            qty = 0;
        }
        amount = rtpl * qty;
        $('#tblmilkcollectiontemp-amount').val(amount);
    }

    $('.rtpl_validate select').change(function(){
        rtpl();
    });
    $('.rtpl_validate input').change(function(){
        rtpl();
    });
    function rtpl(){
        var dcs = $('#tblmilkcollectiontemp-dcs_code').val();
        var milk_type = $('#tblmilkcollectiontemp-milk_type_code').val();
        var milk_quality_type = $('#tblmilkcollectiontemp-milk_quality_type_code').val();
        var dt_date = $('#tblmilkcollectiontemp-date_time_of_collection').val();
        var shift = $('#tblmilkcollectiontemp-shift').val();
        var fat = $('#tblmilkcollectiontemp-fat').val();
        var snf = $('#tblmilkcollectiontemp-snf').val();
        if(dcs != '' && milk_type != '' && milk_quality_type != '' && dt_date!= '' && shift != '' && fat != '' && snf != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-rtpl']) . "',
                data: {'dcs_code':dcs,'milk_type':milk_type,'milk_quality_type':milk_quality_type,'dt_date':dt_date,'shift':shift,'fat':fat,'snf':snf},
                success: function(data) {   
                      var obj = $.parseJSON(data);
                      if (obj.status == 'success')
                      {
                            $('#tblmilkcollectiontemp-rtpl').val(obj.data.list.rtpl);
                            $('#tblmilkcollectiontemp-rate_code').val(obj.data.list.purchase_rate_code);
                      }else{
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>RTPL Not Available</span></div></div>');
                            $('#tblmilkcollectiontemp-rtpl').val('');
                            $('#tblmilkcollectiontemp-rate_code').val('');
                      }
                },
                error:function(data){

                }
            });
        }else{
            $('#tblmilkcollectiontemp-rtpl').val('');
            $('#tblmilkcollectiontemp-rate_code').val('');
        }
    }
    
$('#tblmilkcollectiontemp-dcs_code').on('change', function(){
    validateMember();
    $('#tblmilkcollectiontemp-member_code').val('');
    reloadGrid();
});
$('#tblmilkcollectiontemp-member_code').on('change', function(){
    validateMember();
});

function validateMember(){
    var dcs = $('#tblmilkcollectiontemp-dcs_code').val();
    var member = $('#tblmilkcollectiontemp-member_code').val();
    if(dcs != '' && member!=''){
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/collection/tbl-milk-collection-temp/validate-member']) . "',
            data: {'dcs_code' : dcs, 'member_code' : member},
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if(obj1.status == 'success'){
                
                } else if(obj1.status == 'error'){ 
                
                        bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>" . Yii::t('app', 'Please enter valid Member Code') . "</span></div></div>',function(){
                            bootbox.hideAll();
                            $('#tblmilkcollectiontemp-member_code').focus().select();
                        });
                }
            },
        });
    }
}

function reloadGrid(){
    var url = '" . Url::to(['/collection/tbl-milk-collection-temp/create']) . "';
    $.pjax.reload({container: '#milk-coll-temp-grid', timeout: false, url: url + '?' + $('#milk-coll-temp-form').serialize() });
}

";
$this->registerJs($script, View::POS_END, 'milk-collection-temp');
?>