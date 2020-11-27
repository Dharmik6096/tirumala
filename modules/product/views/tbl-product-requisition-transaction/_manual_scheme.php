<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<?php
$model_name = str_replace('\\', '_', $schememodal->className());
$cname = explode('_', $model_name);
$cname = end($cname);
$nameforid = strtolower($cname);

$form = ActiveForm::begin(['options' => [
                'class' => 'popup-form',
                'id' => 'download-sample',
            ], 'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => FALSE,
            'validateOnSubmit' => FALSE,
            'fieldConfig' => [
            //'labelOptions' => [ 'class' => false],
        ]]);
?>
<div class="modal modal-default fade" id="sampleModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Manual Product'); ?></h4>
            </div>

            <div class="modal-body">
                <div class="panel panel-main">
                    <div class="panel-heading"><?php echo Yii::t('app', 'Add Product'); ?></div>

                    <?php echo $form->errorSummary($schememodal, ['id' => 'error-summary']); ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <?= Yii::$app->autocomplete->generalAutocomplete($schememodal, $form, 'product_code', 'Select Product', 'tbl-product/load-productswef'); ?>
                            </div>                           
                            <?= $form->field($schememodal, 'uom', ['options' => ['placeholder' => $schememodal->uom, 'class' => 'form-group col-sm-6']])->textInput(['readonly' => true]) ?>
                            <?= $form->field($schememodal, 'quantity', ['options' => ['class' => 'form-group col-sm-6 number-validate']])->textInput() ?>
                            <?= $form->field($schememodal, 'passon_to_member', ['options' => ['class' => 'form-group col-sm-6'], 'template' => '{label}<div class="checkbox">{input}</div>{error}{hint}',])->checkbox(); ?>
                            <div class="clearfix"></div>                              
                            <?= $form->field($schememodal, 'provisional_rate', ['options' => ['class' => 'form-group col-sm-6 number-validate']])->textInput() ?>
                            <?= $form->field($schememodal, 'provisional_amount', ['options' => ['class' => 'form-group col-sm-6']])->textInput(['readonly' => true]) ?>

                            <?= Html::hiddenInput('edit_tr', '0', ['id' => 'edit_tr']); ?>
                            <?= Html::hiddenInput('dcs_text', '', ['id' => 'dcs_text']); ?>
                            <?= Html::hiddenInput('requisition_transaction_no', '', ['id' => 'requisition_transaction_no']); ?>
                            <?= Html::hiddenInput('req_code', '', ['id' => 'req_code']); ?>
                        </div>  
                    </div>                   
                    <div class="panel-footer">
                        <?php echo Html::button(Yii::t('app', 'Add'), ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+s', 'button' => 'add', 'onClick' => 'js:AddTransaction();', 'id' => 'addbutton']); ?>                       
                        <button type="button" class="btn btn-default btn-raised close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<script>
    function AddTransaction() {
        var nameforid = '<?php echo $nameforid; ?>';
        var validate = true;
        var message = [];
        var cnt = 0;
        var oldItems = JSON.parse(localStorage.getItem('transactionsArray')) || [];
        var no = $('#edit_tr').val();
        var product = $('#' + nameforid + '-product_code').val();
        var qty = $('#' + nameforid + '-quantity').val();
        var newItem = {
            'product_code': $('#' + nameforid + '-product_code').val(),
            'quantity': $('#' + nameforid + '-quantity').val(),
            'product_name': $('#' + nameforid + '-product_code :selected').text(),
            'dcs_text': $('#dcs_text').val(),
            'requisition_transaction_no': $('#requisition_transaction_no').val(),
            'req_code': $('#req_code').val(),
            'uom': $('#' + nameforid + '-uom').val(),
            'passon_to_member': $('#' + nameforid + '-passon_to_member').val(),
            'provisional_rate': $('#' + nameforid + '-provisional_rate').val(),
            'provisional_amount': $('#' + nameforid + '-provisional_amount').val(),
        };
        if (product == '' || qty == '') {
            message[cnt] = '<?php echo Yii::t('app\validation', 'All Fields are required.'); ?>';
            cnt++;
            validate = false;
        }
        if (validate) {
            oldItems.push(newItem);
            localStorage.setItem('transactionsArray', JSON.stringify(oldItems));

            var content = '';
            var data = JSON.parse(localStorage.getItem('transactionsArray'));
            // $.each(data, function (index, value) {
            var block = '';
            if (newItem.dcs_text == '') {
                block = '<tr class=\"success\"><td>' + newItem.product_name + '</td><td>' + newItem.quantity + '</td><td>' + newItem.provisional_rate + '</td><td>' + newItem.uom + '</td><td>' + newItem.provisional_amount + '</td><td></td><td>' + newItem.quantity + '</td><td></td><td>Sent</td><td></td><td></td></tr>';
                // block = '<tr class=\"success\"><td>' + newItem.product_name + '</td><td>' + newItem.quantity + '</td><td>0</td><td></td><td>0</td><td></td><td>' + newItem.quantity + '</td><td></td><td>Sent</td><td></td><td></td></tr>';

            } else {
                block = '<tr class=\"success\"><td>' + newItem.req_code + '</td><td>' + newItem.dcs_text + '</td><td>' + newItem.product_name + '</td><td>' + newItem.quantity + '</td><td>' + newItem.provisional_rate + '</td><td>' + newItem.uom + '</td><td>' + newItem.provisional_amount + '</td><td></td><td>' + newItem.quantity + '</td><td></td><td>Sent</td><td></td><td></td></tr>';
            }
            $('#req-accept-form thead').append(block);
            //   });

            $('#scheme_item').val(JSON.stringify(data));
            $('#error-summary').hide();
            $('#' + nameforid + '-quantity').val('');
            $('#dcs_text').val('');
            $('#' + nameforid + '-product_code').find('option:not(:first)').remove().trigger('change');
            $('#' + nameforid + '-uom').val('');
            $('#' + nameforid + '-provisional_rate').val('');
            $('#' + nameforid + '-provisional_amount').val('');
            $('#' + nameforid + '-passon_to_member').attr('checked', false);
            $('#' + nameforid + '-passon_to_member').val('0');
            $('#' + nameforid + '-provisional_rate').attr('readonly', false);
            $('#sampleModal').modal('hide');
            var success = "Scheme Product Added Successfully";
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-question-circle text-primary\'></i></div><div class=\'col-sm-10 padding-left-0\'>' + success + '</div></div>');

        } else {
            $('#error-summary ul').html('');
            for (j = 0; j < message.length; j++) {
                $('#error-summary ul').append('<li>' + message[j] + '</li>');
            }
            $('#error-summary').show();
        }

    }

</script>
<?php
$script = "     
 $('#{$nameforid}-passon_to_member').val(0);   
$('#{$nameforid}-passon_to_member').change(function () {
     var rate = $('#{$nameforid}-provisional_rate').val(0);     
         schemeAmount();
    if(this.checked){
      $('#{$nameforid}-provisional_rate').attr('readonly',true);
      $('#{$nameforid}-passon_to_member').val(1);
    }else{
      $('#{$nameforid}-provisional_rate').attr('readonly',false);
      $('#{$nameforid}-passon_to_member').val(0);
   }
 });

    $('#{$nameforid}-product_code').on('select2:select',function(){   
            var id = $('#{$nameforid}-product_code').val();
            if(id!=''){
                    $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/inventory/tbl-product/conversion-unit']) . "',    
                        data: 'id='+id,
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $('#$nameforid-uom').val(obj1.unit);
                            }
                        }
            });   
            }
    });
    
    $('#{$nameforid}-quantity').on('blur',function(){   
        schemeAmount();
    });
    
    $('#{$nameforid}-provisional_rate').on('blur',function(){   
        schemeAmount();
    });
    
    function schemeAmount(){
        var qty = $('#{$nameforid}-quantity').val();
        var rate = $('#{$nameforid}-provisional_rate').val();     
        var amt = parseFloat(qty)*parseFloat(rate);   
        if(!isNaN(amt)){
            amt=amt.toFixed(2);
            $('#{$nameforid}-provisional_amount').val(amt);
        }
    }
";
$this->registerJs($script, View::POS_END, 'manual-scheme-product');
?>