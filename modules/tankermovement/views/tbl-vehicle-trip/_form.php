<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use softark\duallistbox\DualListbox;
?>
<?php
$form = ActiveForm::begin([
    'id' => 'vehicle-trip-form',
    'validateOnBlur' => FALSE,
    'validateOnChange' => FALSE,
    'enableClientValidation' => true,
    'validateOnSubmit' => true,
]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', FALSE, FALSE); ?>
    </div>


    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblvehicletrip-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Transporter'); ?>
    </div>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('transport_vehicle', $model, $form, 'tblvehicletrip-transporter_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), '', FALSE); ?>
    </div>
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvehicletrip-union_code', 'plant_code', Yii::t('app', 'Source Plant'), true); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 megaSizeDualList">
        <?php
        echo $form->field($model, 'bmc_code', ['options' => ['class' => 'form-group col-sm-12'], 'labelOptions' => ['label' => Yii::t('app', 'Plant/Bmc')]])
            ->widget(DualListbox::className(), [
                'items' => [],
                'options' => [
                    'multiple' => true,
                    'size' => 20
                ],
                'clientOptions' => [
                    'moveOnSelect' => FALSE,
                    'selectedListLabel' => FALSE,
                    'nonSelectedListLabel' => FALSE,
                    'filterPlaceHolder' => '',
                    'sortByInputOrder' => TRUE,
                ],
            ]);
        echo Html::hiddenInput('selected_bmc_seq', '', ['id' => 'selected_bmc_seq']);
        ?>
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
$('#tblvehicletrip-plant_code').on('change',function(){
    setBmcList();
});
function setBmcList() {
    var plant_code = $('#tblvehicletrip-plant_code').val(); 
    if(plant_code==''){
    var options = $('#tblvehicletrip-plant_code option');
    plant_code = $.map(options ,function(option) {
                return option.value;
            });
    }
    $.ajax({
        type: 'post',
        url: '" . Url::to(['/organisation/tbl-dcs-bmc/get-plant-bmc']) . "',    
        data: 'plant_code='+plant_code,
        success: function(data) {
            var obj1 = $.parseJSON(data);
            if (obj1.status == 'success') {                          
                var mccarray =  $('#tblvehicletrip-bmc_code option:selected');
                var selarray =  mccarray.map(function () {
                    return this.value;
                }).get();
            $('#tblvehicletrip-bmc_code option').remove();                              
            var options='';  

            $.each(plant_code, function(index, plant_code) {
                options += '<option value=\"' + plant_code + '-plant' + '\">' + $('#tblvehicletrip-plant_code option[value=\"' + plant_code + '\"]').text() + ' - PLANT</option>';
            });          
            $.each(obj1.data, function(index, value) {
                    if(jQuery.inArray(index,selarray) == -1){   
                        options += '<option value=\"'+index+'\">'+value+'</option>';  
                    }
            });  
            var bmc_array = [];
            var bmc_array_nonsel = {};
            mccarray.each(function(){
                var val = $(this).attr('value');
                var txt = $(this).text();
                var dataindex = $(this).attr('data-sortindex');
                bmc_array[dataindex]= val + '~~~' + txt ;
                bmc_array_nonsel[val]=txt;
            });   
            $.each(bmc_array_nonsel, function(index, value) {
                options += '<option value=\"'+index+'\">'+value+'</option>'; 
            });            
            $.each(bmc_array, function(index, value) {
                if(value!=''){
                    var valtxt=value.split('~~~')
                    options += '<option value=\"'+valtxt[0]+'\"  data-sortindex=\"'+index+'\" selected>'+valtxt[1]+'</option>';
                }
            });          
            $('#tblvehicletrip-bmc_code').html(options);
            $('#tblvehicletrip-bmc_code').bootstrapDualListbox('refresh', true); 
            }
        }
    });           
}
$('#vehicle-trip-form').submit(function(e) {
    var bmcarray = '';                                      
    var options = $('#tblvehicletrip-bmc_code option:selected');
    options.each(function(){
        bmcarray += $(this).attr('data-sortindex')+'~~~'+$(this).attr('value')+':::';
    });
    $('#selected_bmc_seq').val(bmcarray);      
});
";
$script .= "$('#tblvehicletrip-bmc_code').change(function () {
var mccarray =  $('#tblvehicletrip-bmc_code option:selected');
var nonselarray =  $('#tblvehicletrip-bmc_code option:not(:selected)').map(function () {return this.value;}).get();                        
var bmc_array_sel = {};
    mccarray.each(function(){
                var val = $(this).attr('value');
                        var txt = $(this).text();
                        bmc_array_sel[val]=txt;
                    });   
    $.each(bmc_array_sel, function(index, value) {
            if(jQuery.inArray(index,nonselarray) == -1){
                $('#tblvehicletrip-bmc_code').append($('<option></option>').attr('value', index).text(value)); 
            }
            nonselarray.push(index);
    });  
    $('#tblvehicletrip-bmc_code').bootstrapDualListbox('refresh', true);      
});
";

$this->registerJs($script, View::POS_END, 'vehicle-trip-bmc-list');
?>