<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

$disabled = empty($model->bmc_milk_dispatch_code) ? '' : 'disabled';
$bmc_milk_dispatch_code = $model->bmc_milk_dispatch_code;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary([$model, $transaction]); ?>

<?= Html::activeHiddenInput($model, 'bmc_milk_dispatch_code'); ?>
<div class="micro_form">
    <div class="row">
        <div class="col-lg-8 <?= $disabled ?>">
            <div class="col-sm-2 filldata">
                <?= Yii::$app->controls->date($model, $form, 'from_date', '', date('Y-m-d'), false, FALSE, true); ?>
            </div>
            <div class="col-sm-2 shift filldata">
                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', true, false, 'from_shift_code'); ?>
            </div>
            <div class="col-sm-2 filldata">
                <?= Yii::$app->controls->date($model, $form, 'to_date', '', date('Y-m-d'), false, FALSE, true); ?>
            </div>
            <div class="col-sm-2 shift filldata">
                <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', true, false, 'to_shift_code'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbmcmilkdispatch-union_code', 'plant_code', TRUE); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbmcmilkdispatch-plant_code', 'mcc_plant_code', TRUE); ?>
            </div>
            <div class="clearfix"></div>

            <div class="col-sm-2 filldata">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbmcmilkdispatch-mcc_plant_code', 'bmc_code', TRUE); ?>
            </div>
            <div class="col-sm-2 filldata"> 
                <?= Yii::$app->dropdown->depend_dropdown('union_vehicle', $model, $form, 'tblbmcmilkdispatch-union_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), '', FALSE); ?>
            </div>
            <div class="col-sm-2"> 
                <?= $form->field($model, 'trip_code')->textInput(['readonly' => 'readonly']) ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', date('Y-m-d'), false, FALSE, true); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'vehicle_in_time')->widget(MaskedInput::className(), ['mask' => '99:99',]); ?>
            </div>
            <div class="col-sm-2 number-validate"> 
                <?= $form->field($model, 'tare_weight')->textInput() ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-2 number-validate"> 
                <?= $form->field($model, 'gross_weight')->textInput() ?>
            </div>

            <div class="col-sm-2">
                <?= $form->field($model, 'vehicle_out_time')->widget(MaskedInput::className(), ['mask' => '99:99',]); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->dropdown('dispatch_destination', $model, $form, '', TRUE, false, 'destination_type'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->destination_code_list($model, $form, 'tblbmcmilkdispatch-destination_type,tblbmcmilkdispatch-union_code,tblbmcmilkdispatch-bmc_code', 'destination_code', $model->getAttributeLabel('destination_code')); ?>
            </div>
            <!--        <div class="col-sm-2 mt15">
            <?php //$form->field($model, 'is_last_destination', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox();  ?>
                    </div>-->
            <div class="col-sm-4"> 
                <?= $form->field($model, 'remarks')->textInput() ?>
            </div>
        </div>
        <div class="col-lg-4">
            <h5 class="panel-heading mb15"><?= Yii::t('app', 'Purchase Information') ?></h5>
            <div id="purchase-detial">
                <table class="table tab-bordered">
                    <thead>
                        <tr>
                            <th>Milk Type</th>
                            <th>Quality Type</th>
                            <th>Silo No.</th>                 
                            <th>Purchase Qty</th>
                            <th>Balance Qty</th>    
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12">
            <h5 class="panel-heading mb15"><?= Yii::t('app', 'Dispatch Transactions') ?></h5>

            <div class="col-sm-1"> 
                <?= Yii::$app->dropdown->dropdown('milk_type_code', $transaction, $form, '', true, FALSE, 'milk_type_code'); ?>
            </div>
            <div class="col-sm-1"> 
                <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $transaction, $form, '', true, FALSE, 'milk_quality_type_code'); ?>
            </div>
            <div class="col-sm-1"> 
                <?= Yii::$app->dropdown->depend_dropdown('bmc_silos', $transaction, $form, 'tblbmcmilkdispatch-bmc_code', 'form-group col-sm-4', $transaction->getAttributeLabel('bmc_silos_info_code'), ''); ?>
                <?php //Yii::$app->dropdown->dropdown('bmc_silos', $transaction, $form, '', TRUE, false, 'bmc_silos_info_code'); ?>

            </div>
            <div class="col-sm-1"> 
                <?= Yii::$app->dropdown->dropdownStatic('chamber_no', $transaction, $form, 'form-group', $transaction->getAttributeLabel('chamber_no'), false, 'chamber_no', false); ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'dispatch_qty')->textInput() ?>
            </div>
            <div class="col-sm-1"> 
                <?= Yii::$app->dropdown->dropdown('qty_diff_type', $transaction, $form, '', true, false, 'qty_diff_type_code'); ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'qty_diff')->textInput() ?>
            </div>

            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'balance_qty')->textInput() ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'fat')->textInput() ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'snf')->textInput() ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'water')->textInput() ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'temperature')->textInput() ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'clr')->textInput() ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'protein')->textInput() ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'density')->textInput() ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'lactose')->textInput() ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'freezing_point')->textInput() ?>
            </div>

            <div class="col-sm-1"> 
                <?= $form->field($transaction, 'hsn_code')->textInput() ?>
            </div>
            <div class="col-sm-1"> 
                <?= $form->field($transaction, 'seal_no_top')->textInput() ?>
            </div>
            <div class="col-sm-1"> 
                <?= $form->field($transaction, 'seal_no_bottom')->textInput() ?>
            </div>
            <div class="col-sm-1"> 
                <?= $form->field($transaction, 'seal_no_broken')->textInput() ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'dip_open')->textInput() ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'dip_close')->textInput() ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'dip_diff')->textInput() ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'rtpl')->textInput() ?>
            </div>
            <div class="col-sm-1 number-validate"> 
                <?= $form->field($transaction, 'amount')->textInput(['readonly' => 'readonly']) ?>
            </div>
            <div id="transactions-from">

            </div>
        </div>
        <div class="col-sm-12 shortcut-main mt15" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
                <?= Yii::$app->controls->reset(); ?>
                <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="col-lg-12">
        <h5 class="panel-heading mb15"><?= Yii::t('app', 'Dispatch Transactions Detail') ?></h5>
        <div id="transactions-detial">

        </div>
    </div>
</div>
<?php
$script = "$(document).ready(function(){
var bmc_milk_dispatch_code = $('#tblbmcmilkdispatch-bmc_milk_dispatch_code').val();
 if(bmc_milk_dispatch_code!=''){
$('#tblbmcmilkdispatchtxn-milk_type_code').focus(); 
}
});


 $(document).on('change','#tblbmcmilkdispatchtxn-dispatch_qty,#tblbmcmilkdispatchtxn-rtpl', function() {
 var rtpl=$('#tblbmcmilkdispatchtxn-rtpl').val();
 var dispatch_qty=$('#tblbmcmilkdispatchtxn-dispatch_qty').val();
 if(rtpl !='' && dispatch_qty!=''){
  $('#tblbmcmilkdispatchtxn-amount').val(parseFloat(rtpl*dispatch_qty).toFixed(2))
 } 
});

";

$script .= "
    $(document).on('change','.filldata', function() {
        var bmc_code = $('#tblbmcmilkdispatch-bmc_code').val();
        var union_code = $('#tblbmcmilkdispatch-union_code').val();
        var from_date = $('#tblbmcmilkdispatch-from_date').val();
        var from_shift = $('#tblbmcmilkdispatch-from_shift_code').val();
        var to_date = $('#tblbmcmilkdispatch-to_date').val();
        var to_shift = $('#tblbmcmilkdispatch-to_shift_code').val();
        var bmc_milk_dispatch_code = $('#tblbmcmilkdispatch-bmc_milk_dispatch_code').val();
        var vehicle_code = $('#tblbmcmilkdispatch-vehicle_code').val();
       if(from_date != '' && from_shift !='' && to_date != '' && to_shift !='' && bmc_code != '' && vehicle_code !=''){
            $('#loadercontent').show();
            $('#pageloader').show();
            $('#purchase-detial').html('');
            $('#transactions-from').html('');
            $('#transactions-detial').html('');  
           if(bmc_milk_dispatch_code == ''){
            CheckTrip(bmc_code,from_date,from_shift,to_date,to_shift,vehicle_code,bmc_milk_dispatch_code,union_code)
            }else{
            BindData(bmc_code,from_date,from_shift,to_date,to_shift,vehicle_code,bmc_milk_dispatch_code,union_code);
            }
        }      
    });
    
   function CheckTrip(bmc_code,from_date,from_shift,to_date,to_shift,vehicle_code,bmc_milk_dispatch_code,union_code){
      $.ajax({
                type: 'get',
                url: '" . Url::to(['check-trip']) . "',
                data: {'from_date' : from_date,'from_shift':from_shift,'to_date' : to_date,'to_shift':to_shift,'bmc_code' : bmc_code,'vehicle_code' : vehicle_code},             
                success: function(data) {
                  var data=$.parseJSON(data);
                  if (data.status == 'success'){   
                    $('#tblbmcmilkdispatch-trip_code').val(data.trip_code);
                    BindData(bmc_code,from_date,from_shift,to_date,to_shift,vehicle_code,bmc_milk_dispatch_code,union_code); 
                }else {
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                 bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>' + data.msg + '</span></div></div>');
                }
               } 
            });   
   }  
  
    function BindData(bmc_code,from_date,from_shift,to_date,to_shift,vehicle_code,bmc_milk_dispatch_code,union_code){
       
         $.ajax({
                type: 'get',
                url: '" . Url::to(['purchase-detail']) . "',
                data: {'from_date' : from_date,'from_shift':from_shift,'to_date' : to_date,'to_shift':to_shift,'bmc_code' : bmc_code},             
                success: function(data) {
                  $('#purchase-detial').html(data);                                                                 
                }
            });
        $.ajax({
                type: 'get',
                url: '" . Url::to(['transaction-form']) . "',
                data: {'bmc_code' : bmc_code,'union_code':union_code},             
                success: function(data) {
                  $('#transactions-from').html(data);                                                                 
                }
            });            
        $.ajax({
                type: 'get',
                url: '" . Url::to(['transaction-detail']) . "',
                data: {'bmc_milk_dispatch_code' : bmc_milk_dispatch_code},             
                success: function(data) {
                  $('#transactions-detial').html(data);
                  $('#loadercontent').hide();
                  $('#pageloader').hide();  
                },
                error: function(data) {  
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });     
    }
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>