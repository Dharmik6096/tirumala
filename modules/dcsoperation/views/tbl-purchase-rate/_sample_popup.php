<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
?>
<?php
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
//$form = ActiveForm::begin(['options' => [
//                'class' => 'popup-form',
//                'id' => 'download-sample',
////                'action' => ['download-templet'],
////                'method' => 'post',
//            ], 'fieldConfig' => [
//        ]]);
?>
<div class="modal modal-default fade" id="sampleModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Rate Details'); ?></h4>
            </div>

            <div class="modal-body">
                <div class="panel panel-main">
                    <div class="panel-heading"><?php echo Yii::t('app', 'Rate Details'); ?></div>
                    <div class="panel-body">
                        <div class="panel-subheading">
                            <?php echo $form->errorSummary($purchaseBasedModel, ['id' => 'error-summary']); ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= Yii::$app->dropdown->dropdown('rate_type_code', $purchaseBasedModel, $form, '', 'Rate Type', false, '[0]rate_type_code'); ?>                                  
                                </div>
                                <div class="col-sm-6">
                                    <?= Yii::$app->dropdown->dropdown('milk_type_code', $purchaseBasedModel, $form, '', 'Milk Type', false, '[0]milk_type_code'); ?>
                                </div>
                                <?= Html::hiddenInput('edit_tr', '0', ['id' => 'edit_tr']); ?>
                                <?= Html::hiddenInput('range_table', '', ['id' => 'range_table']); ?>
                                <div id="range"></div>    
                                <div class="col-sm-9 mt10">
                                    <?php echo Html::button(Yii::t('app', 'Add'), ['class' => 'mt15 btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+s', 'button' => 'add', 'onClick' => 'js:AddTransaction();', 'id' => 'addbutton']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="ex2-grid">
                            <div class='table-responsive'>
                                <table id="test-table" class='table table-bordered table-striped table-main'>
                                    <tr id="test-header-tr">
                                        <th class='width15'><?=Yii::t('app','Rate Type')?></th>
                                        <th class='width15'><?=Yii::t('app','Quality Param')?></th>
                                        <th class='width15'><?=Yii::t('app','Milk Type')?></th>
                                        <th class='width10'><?=Yii::t('app','Start Range')?></th>
                                        <th class='width15'><?=Yii::t('app','End Range')?></th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">

                        <button type="button" class="btn btn-default btn-raised close-import" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                        <?php echo Html::submitButton(Yii::t('app', 'Download'), ['class' => 'btn btn-default', 'name' => 'templet-download']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    $('#download-sample').submit(function() {
    $('#sampleModal').modal('hide');
});
       $('#tblpurchaseratebased-0-rate_type_code').on('change',function(e){
        $('#range').empty();       
        var rateType = $('#tblpurchaseratebased-0-rate_type_code :selected').text();     
        var field_before = '<div class=\"col-sm-3\"><div class=\"form-group\">';
       // var field_after = '<div class=\"help-block\"></div></div></div>';
        var field_after = '';
      if($('#tblpurchaseratebased-0-rate_type_code').val()!=''){
        $.each(rateType.split('+'), function(index, item)
        {         
           $('#range').append(field_before + '<label class=\"control-label\">' + item + ' Start*</label><input type=\"text\" name=\"TblPurchaseRateBased['+index+'][start_range]\" id=\"tblpurchaseratebased-'+index+'-start_range\" class=\"form-control number-validate\">' + field_after);
           $('#range').append(field_before + '<label class=\"control-label\">' + item + ' End*</label><input type=\"text\" name=\"TblPurchaseRateBased['+index+'][end_range]\" id=\"tblpurchaseratebased-'+index+'-end_range\" class=\"form-control number-validate\">' + field_after);           
      var specialDecimalKeys = new Array();
        specialDecimalKeys.push(8);
$('.number-validate').bind('keypress', function (e) {
            var keyCode = e.which ? e.which : e.keyCode
            var ret = ((keyCode >= 48 && keyCode <= 57) || (specialDecimalKeys.indexOf(keyCode) != -1) || keyCode == 9 || keyCode == 46);
            return ret;
            /*var v = this.value;
             var value = new RegExp('^\d+(?:\.\d{2})?$');
             alert(value.test(v));
             return value;*/
        });
        });
      }
    });
";
$this->registerJs($script, View::POS_END, 'sample-download');
?>
<script>
    function AddTransaction() {
        var validate = true;
        var message = [];
        var cnt = 0;
        var oldItems = JSON.parse(localStorage.getItem('transactionsArray')) || [];
        var no = $('#edit_tr').val();
        var rate = $('#tblpurchaseratebased-0-rate_type_code').val();
        var milk = $('#tblpurchaseratebased-0-milk_type_code').val();
        var newItem = {
            'rate_type_code': $('#tblpurchaseratebased-0-rate_type_code option:selected').text(),
            'milk_type': $('#tblpurchaseratebased-0-milk_type_code option:selected').text(),
            'fat_start': $('#tblpurchaseratebased-0-start_range').val(),
            'fat_end': $('#tblpurchaseratebased-0-end_range').val(),
            'snf_start': $('#tblpurchaseratebased-1-start_range').val(),
            'snf_end': $('#tblpurchaseratebased-1-end_range').val(),
        };

        if (rate == '' || milk == '' || newItem.fat_start == '' || newItem.fat_end == '' || newItem.snf_start == '' || newItem.snf_end == '') {
            message[cnt] = 'All Fields are required.';
            cnt++;
            validate = false;
        } else {
            var fat_start = parseFloat(newItem.fat_start);
            var fat_end = parseFloat(newItem.fat_end);
            var snf_start = parseFloat(newItem.snf_start);
            var snf_end = parseFloat(newItem.snf_end);
            var milk_type = newItem.milk_type;

            if (fat_end < fat_start) {
                message[cnt] = 'End Range cannot be less then Start Range';
                cnt++;
                validate = false;
            } else if (fat_end == fat_start) {
                message[cnt] = 'End Range and Start Range cannot be same.';
                cnt++;
                validate = false;
            }
            if (snf_end < snf_start) {
                message[cnt] = 'End Range cannot be less then Start Range';
                cnt++;
                validate = false;
            } else if (snf_end == snf_start) {
                message[cnt] = 'End Range and Start Range cannot be same.';
                cnt++;
                validate = false;
            }
            if (validate) {
                var local = JSON.parse(localStorage.getItem('transactionsArray'));
                if (localStorage.getItem('transactionsArray')) {
                    for (i = 0; i < local.length; i++) {
                        if (local[i]['milk_type'] == milk_type) {
                            message[cnt] = 'Milk Type already added.';
                            cnt++;
                            validate = false;
                        }
                    }
                }
            }
        }
        if (validate) {
            oldItems.push(newItem);
            localStorage.setItem('transactionsArray', JSON.stringify(oldItems));

            var content = '';
            var data = JSON.parse(localStorage.getItem('transactionsArray'));
            $.each(data, function (index, value) {
                var qltyparam = value.rate_type_code.split('+');
                content += '<tr><td class=\'width15\'>' + value.rate_type_code + '</td><td class=\'width15\'>' + qltyparam[0] + '</td><td class=\'width15\'>' + value.milk_type + '</td><td class=\'width10\'>' + value.fat_start + '</td><td class=\'width15\'>' + value.fat_end + '</td></tr>'
                if (typeof (qltyparam[1]) != "undefined" && qltyparam[1] !== null) {
                    content += '<tr><td class=\'width15\'>' + value.rate_type_code + '</td><td class=\'width15\'>' + qltyparam[1] + '</td><td class=\'width15\'>' + value.milk_type + '</td><td class=\'width15\'>' + value.snf_start + '</td><td class=\'width15\'>' + value.snf_end + '</td></tr>'
                }
            });

            $('#test-table tr:not(:first)').remove();
            $(content).insertAfter('#test-header-tr');
            $('#range_table').val(JSON.stringify(data));
            $('#range').empty();
             $('#error-summary').hide();
            $('#tblpurchaseratebased-0-rate_type_code').val('');
            $('#tblpurchaseratebased-0-milk_type_code').val('');
        } else {
            $('#error-summary ul').html('');
            for (j = 0; j < message.length; j++) {
                $('#error-summary ul').append('<li>' + message[j] + '</li>');
            }
            $('#error-summary').show();
        }

    }

</script>