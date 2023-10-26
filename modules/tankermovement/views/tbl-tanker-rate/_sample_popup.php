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
                                <div class="col-sm-4">
                                    <?php
                                    $rate_type = array("FAT+SNF", "QTY");
                                    echo $form->field($purchaseBasedModel, 'rate_type_code')->dropDownList($rate_type, ['prompt' => Yii::t('app', 'Select Rate Type')])->label(Yii::t('app', 'Rate Type'));
                                    ?>                                  
                                </div>
                                <div class="col-sm-4">
                                    <?= Yii::$app->dropdown->dropdown('milk_type_code', $purchaseBasedModel, $form, '', 'Milk Type', false, 'milk_type_code'); ?>
                                </div>
                                <div class="col-sm-4">
                                    <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $purchaseBasedModel, $form, '', 'Milk Quality Type', false, 'milk_quality_type_code'); ?>
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
                                        <th class='width15'><?= Yii::t('app', 'Rate Type') ?></th>
                                        <th class='width15'><?= Yii::t('app', 'Quality Param') ?></th>
                                        <th class='width15'><?= Yii::t('app', 'Milk Type') ?></th>
                                        <th class='width10'><?= Yii::t('app', 'Std Fat') ?></th>
                                        <th class='width15'><?= Yii::t('app', 'Std SNF') ?></th>
                                        <th class='width15'><?= Yii::t('app', 'Base Rate') ?></th>
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
       $('#tbltankerratebased-rate_type_code').on('change',function(e){
        $('#range').empty();       
       var rateType = $('#tbltankerratebased-rate_type_code :selected').text();     
        var field_before = '<div class=\"col-sm-3\"><div class=\"form-group\">';
        var field_after = '';
        $('#range').append(field_before + '<label class=\"control-label\">Std Fat*</label><input type=\"text\" name=\"TblPurchaseRateBased[std_fat]\" id=\"tbltankerratebased-std_fat\" class=\"form-control number-validate\">' + field_after);
             $('#range').append(field_before + '<label class=\"control-label\">Std SNF*</label><input type=\"text\" name=\"TblPurchaseRateBased[std_snf]\" id=\"tbltankerratebased-std_snf\" class=\"form-control number-validate\">' + field_after);
             $('#range').append(field_before + '<label class=\"control-label\">Base Rate*</label><input type=\"text\" name=\"TblPurchaseRateBased[base_rate]\" id=\"tbltankerratebased-base_rate\" class=\"form-control number-validate\">' + field_after);
             
      if($('#tbltankerratebased-rate_type_code').val()!=''){
      console.log($('#tbltankerratebased-rate_type_code').val());
       if (rateType== 'FAT+SNF')
        {         
           $('#range').append(field_before + '<label class=\"control-label\">Fat Ratio*</label><input type=\"text\" name=\"TblTankerRateBased[fat_ratio]\" id=\"tbltankerratebased-fat_ratio\" class=\"form-control number-validate\">' + field_after);
            $('#range').append(field_before + '<label class=\"control-label\">SNF Ratio*</label><input type=\"text\" name=\"TblTankerRateBased[snf_ratio]\" id=\"tbltankerratebased-snf_ratio\" class=\"form-control number-validate\">' + field_after);
             
            $('#range').append(field_before + '<label class=\"control-label\">Fat Rate*</label><input type=\"text\" name=\"TblTankerRateBased[fat_rate]\" id=\"tbltankerratebased-fat_rate\" readonly=\"true\" disable=\"true\">' + field_after);
            $('#range').append(field_before + '<label class=\"control-label\">SNF Rate*</label><input type=\"text\" name=\"TblTankerRateBased[snf_rate]\" id=\"tbltankerratebased-snf_rate\" readonly=\"true\" disable=\"true\">' + field_after);

            var specialDecimalKeys = new Array();
                specialDecimalKeys.push(8);
            $('.number-validate').bind('keypress', function (e) {
            var keyCode = e.which ? e.which : e.keyCode
            var ret = ((keyCode >= 48 && keyCode <= 57) || (specialDecimalKeys.indexOf(keyCode) != -1) || keyCode == 9 || keyCode == 46);
            return ret;
        });
       }
       
        if (rateType== 'QTY')
        {         
           $('#range').append(field_before + '<label class=\"control-label\">QTY Rate*</label><input type=\"text\" name=\"TblTankerRateBased[qty_rate]\" id=\"tbltankerratebased-qty_rate\" class=\"form-control number-validate\">' + field_after);
             var specialDecimalKeys = new Array();
                specialDecimalKeys.push(8);
            $('.number-validate').bind('keypress', function (e) {
            var keyCode = e.which ? e.which : e.keyCode
            var ret = ((keyCode >= 48 && keyCode <= 57) || (specialDecimalKeys.indexOf(keyCode) != -1) || keyCode == 9 || keyCode == 46);
            return ret;
        });
       }
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
        var rate = $('#tbltankerratebased-rate_type_code').val();
        var milk = $('#tbltankerratebased-milk_type_code').val();
        var milk_quality = $('#tbltankerratebased-milk_quality_type_code').val();
        var newItem = {
            'rate_type_code': $('#tbltankerratebased-rate_type_code option:selected').text(),
            'milk_type': $('#tbltankerratebased-milk_type_code option:selected').text(),
            'milk_quality_type_code': $('#tbltankerratebased-milk_quality_type_code option:selected').text(),
            'std_fat': $('#tbltankerratebased-std_fat').val(),
            'std_snf': $('#tbltankerratebased-std_snf').val(),
            'base_rate': $('#tbltankerratebased-base_rate').val(),
            'fat_rate': $('#tbltankerratebased-fat_rate').val(),
            'snf_rate': $('#tbltankerratebased-snf_rate').val(),
            'qty_rate': $('#tbltankerratebased-qty_rate').val(),
            'fat_ratio': $('#tbltankerratebased-fat_ratio').val(),
            'snf_ratio': $('#tbltankerratebased-snf_ratio').val(),

//            'fat_start': $('#tbltankerratebased-').val(),
//            'fat_end': $('#tbltankerratebased-end_range').val(),
//            'snf_start': $('#tbltankerratebased-start_range').val(),
//            'snf_end': $('#tbltankerratebased-end_range').val(),
        };

        if (rate == '' || milk == '' || milk_quality == '' || newItem.std_fat == '' || newItem.std_snf == '' || newItem.base_rate == '') {
            message[cnt] = 'All Fields are required.';
            cnt++;
            validate = false;
        } else {
            var fat_rate = newItem.fat_ratio * newItem.base_rate / newItem.std_fat;
            var snf_rate = newItem.snf_ratio * newItem.base_rate / newItem.std_fat;
            var fatfield = document.getElementById('#tbltankerratebased-fat_rate');
            var snffield = document.getElementById('#tbltankerratebased-snf_rate');
            if (fatfield != null)
            {
                fatfield.value = fat_rate;
            }
            if (snffield != null)
            {
                snffield.value = snf_rate;
            }

            var milk_type = newItem.milk_type;
            var milk_quality = newItem.milk_quality_type_code;

//            if (fat_end < fat_start) {
//                message[cnt] = 'End Range cannot be less then Start Range';
//                cnt++;
//                validate = false;
//            } else if (fat_end == fat_start) {
//                message[cnt] = 'End Range and Start Range cannot be same.';
//                cnt++;
//                validate = false;
//            }
//            if (snf_end < snf_start) {
//                message[cnt] = 'End Range cannot be less then Start Range';
//                cnt++;
//                validate = false;
//            } else if (snf_end == snf_start) {
//                message[cnt] = 'End Range and Start Range cannot be same.';
//                cnt++;
//                validate = false;
//            }
            if (validate) {
                var local = JSON.parse(localStorage.getItem('transactionsArray'));
                console.log(local);
                if (localStorage.getItem('transactionsArray')) {
                    for (i = 0; i < local.length; i++) {
                        if (local[i]['milk_type'] == milk_type && local[i]['milk_quality_type_code'] == milk_quality) {
                            message[cnt] = 'Milk Type & quality already added.';
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
                // var qltyparam = value.rate_type_code.split('+');
                content += '<tr><td class=\'width15\'>' + value.rate_type_code + '</td><td class=\'width15\'>' + value.milk_quality_type_code + '</td><td class=\'width15\'>' + value.milk_type + '</td><td class=\'width15\'>' + value.std_fat + '</td><td class=\'width15\'>' + value.std_snf + '</td><td class=\'width15\'>' + value.base_rate + '</td></tr>';

            });

            $('#test-table tr:not(:first)').remove();
            $(content).insertAfter('#test-header-tr');
            $('#range_table').val(JSON.stringify(data));
            $('#range').empty();
            $('#error-summary').hide();
            $('#tbltankerratebased-rate_type_code').val('');
            $('#tbltankerratebased-0-milk_type_code').val('');
        } else {
            $('#error-summary ul').html('');
            for (j = 0; j < message.length; j++) {
                $('#error-summary ul').append('<li>' + message[j] + '</li>');
            }
            $('#error-summary').show();
        }

    }

</script>