<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\vsp\models\TblHeadLoad */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Head Load');
$this->title = Yii::t('app', $title);
$button = Yii::$app->label->button($type);
$disabled = $type == 'create' ? FALSE : TRUE;
?>
<?php
$form = ActiveForm::begin(['options' => [
                'id' => 'head-load-form',
                'field-class' => 'form-group col-sm-3'
            ], 'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
            //'labelOptions' => [ 'class' => false],
        ]]);
?>
<div class="panel-body">
    <div class="panel-subheading" id="headdiv">
        <?php echo $form->errorSummary($model, ['id' => 'error-summary']); ?>
        <div class="row theme_border_left theme_border_right">
            <div class="col-md-12 padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading">Head load</h4>
                </div>
                    <div class="col-sm-12" id="union">
                        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $disabled); ?>
                    </div>
                    <?php //Yii::$app->dropdown->dropdown('criteria_type_code', $model, $form, 'form-group col-sm-3', 'Criteria Type', $disabled); ?>
                    <?= $form->field($model, 'fix_value', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['class' => 'form-control number-validate']) ?>
                    <?= $form->field($model, 'min_km', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['class' => 'form-control number-validate']) ?>
                    <?= $form->field($model, 'min_qty', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['class' => 'form-control number-validate']) ?>
                    <?= $form->field($model, 'max_qty', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['class' => 'form-control number-validate']) ?>

            <?= $form->field($model, 'criteria_description', ['options' => ['class' => 'form-group col-sm-2',]])->textArea(['onblur' => 'js:Allowadd();']) ?>        
            </div>
        </div>
    </div>
    <div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="panel-subheading" id="trdiv">
        <?php //echo $form->errorSummary($transaction);  ?>
        <div class="row">
            <div class="col-md-12 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h4 class="theme-box-heading"><?php echo Yii::t('app', $title) . ' ' . Yii::t('app', 'Transaction') ?></h4>
                </div>
                <?= Html::hiddenInput('transaction_code', '0', ['id' => 'transaction_code']); ?>
                <?= Html::hiddenInput('edit_tr', '0', ['id' => 'edit_tr']); ?>
                <?= $form->field($transaction, 'from_km', ['options' => ['class' => 'form-group col-sm-1']])->textInput(['class' => 'form-control number-validate']) ?>
                <?= $form->field($transaction, 'to_km', ['options' => ['class' => 'form-group col-sm-1']])->textInput(['class' => 'form-control number-validate']) ?>
                <?= $form->field($transaction, 'from_qty', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['class' => 'form-control number-validate']) ?>
                <?= $form->field($transaction, 'to_qty', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['class' => 'form-control number-validate']) ?>
                <?= Html::hiddenInput('load_transaction', '', ['id' => 'load_transaction']); ?>
                <?= $form->field($transaction, 'km_value', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['class' => 'form-control number-validate']) ?>
                <?= $form->field($transaction, 'value', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['class' => 'form-control number-validate']) ?>
                <div class="col-sm-2 padding_top_20">
                    <?php echo Html::button(Yii::t('app', 'Add Transaction'), ['class' => 'btn btn-primary apply-shortcut', 'shortcut_key' => 'ctrl+alt+s', 'button' => 'add', 'onClick' => 'js:AddTransaction();', 'id' => 'addbutton']); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="ex-grid">
        <div class="table-responsive kv-grid-container grid-height">
            <table id='transactions' class="table table-bordered table-main kv-grid-table table-hover kv-table-wrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="display:none;"><?= Yii::t('app', 'Code') ?></th>
                        <th><?= Yii::t('app', 'From Km') ?></th>
                        <th><?= Yii::t('app', 'To Km') ?></th>
                        <th><?= Yii::t('app', 'From Qty') ?></th>
                        <th><?= Yii::t('app', 'To Qty') ?></th>
                        <th><?= Yii::t('app', 'Km Rate') ?></th>
                        <th><?= Yii::t('app', 'Kg Rate') ?></th>
                        <th class="text-center"><?= Yii::t('app', 'Action') ?></th>
                    </tr>
                </thead>
                <tbody id='transactionsbody'>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
<div class=" shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <?= Yii::$app->controls->save($button, $model); ?>
    <?= Html::button(Yii::t('app', 'reset'), ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+r', 'onClick' => 'window.location.reload();']); ?>
    <?= Yii::$app->controls->cancel($model, 'tbl-head-load/index'); ?>
</div>
<?php ActiveForm::end(); ?>
<script>
    function Allowadd() {
        if ($('#tblheadload-min_qty').val() != '' && $('#tblheadload-max_qty').val() != '' && $('#tblheadload-min_km').val() != '' && $('#tblheadload-fix_value').val() != '' && $('#tblheadload-criteria_description').val() != '') {
            $('#headdiv :input').attr('disabled', true);
            $('#trdiv :input').attr('disabled', false);
            $('#tblheadloadtransaction-from_km').focus();
        }
    }
    function AddTransaction() {
        var validate = true;
        var message = [];
        var cnt = 0;
        var oldItems = JSON.parse(localStorage.getItem('transactionsArray')) || [];
        var no = $('#edit_tr').val();
        var newItem = {
            'head_load_transaction_code': $('#transaction_code').val(),
            'from_km': $('#tblheadloadtransaction-from_km').val(),
            'to_km': $('#tblheadloadtransaction-to_km').val(),
            'from_qty': $('#tblheadloadtransaction-from_qty').val(),
            'to_qty': $('#tblheadloadtransaction-to_qty').val(),
            'value': $('#tblheadloadtransaction-value').val(),
            'km_value': $('#tblheadloadtransaction-km_value').val(),
        };
        if (newItem.from_km == '' || newItem.to_km == '' || newItem.from_qty == '' || newItem.to_qty == '' || newItem.value == '' || newItem.km_value == '') {
            message[cnt] = '<?php echo Yii::t('app\validation', 'Qty/Km/Rate Cannot be blank.'); ?>';
            cnt++;
            validate = false;
        } else {
            var from_km = parseFloat(newItem.from_km);
            var from_qty = parseFloat(newItem.from_qty);
            var to_km = parseFloat(newItem.to_km);
            var to_qty = parseFloat(newItem.to_qty);
            if (to_km < from_km) {
                message[cnt] = '<?php echo Yii::t('app\validation', 'To Km cannot be less then From Km'); ?>';
                cnt++;
                validate = false;
            }
//            else if (to_km == from_km) {
//                message[cnt] = '<?php //echo Yii::t('app\validation', 'To Km and From Km cannot be same.');          ?>';
//                cnt++;
//                validate = false;
//            }
            if (to_qty < from_qty) {
                message[cnt] = '<?php echo Yii::t('app\validation', 'To Qty cannot be less then From Qty.'); ?>';
                cnt++;
                validate = false;
            }
//            else if (to_qty == from_qty) {
//                message[cnt] = '<?php //echo Yii::t('app\validation', 'To Qty and From Qty cannot be same.');          ?>';
//                cnt++;
//                validate = false;
//            }
            if (validate) {
                var local = JSON.parse(localStorage.getItem('transactionsArray'));
                if (localStorage.getItem('transactionsArray')) {
                    for (i = 0; i < local.length; i++) {
                        if (i != no - 1) {
                            if ((parseFloat(local[i]['from_km']) >= from_km && parseFloat(local[i]['from_km']) <= to_km) || (from_km >= parseFloat(local[i]['from_km']) && from_km <= parseFloat(local[i]['to_km']))) {
                                if ((parseFloat(local[i]['from_qty']) >= from_qty && parseFloat(local[i]['from_qty']) <= to_qty) || (from_qty >= parseFloat(local[i]['from_qty']) && from_qty <= parseFloat(local[i]['to_qty']))) {
                                    message[cnt] = '<?php echo Yii::t('app\validation', 'Range is conflicting ,please resolve it first.'); ?>';
                                    cnt++;
                                    validate = false;
                                }
                            } else if ((parseFloat(local[i]['from_qty']) >= from_qty && parseFloat(local[i]['from_qty']) <= to_qty) || (from_qty >= parseFloat(local[i]['from_qty']) && from_qty <= parseFloat(local[i]['to_qty']))) {
                                if ((parseFloat(local[i]['from_km']) >= from_km && parseFloat(local[i]['from_km']) <= to_km) || (from_km >= parseFloat(local[i]['from_km']) && from_km <= parseFloat(local[i]['to_km']))) {
                                    message[cnt] = '<?php echo Yii::t('app\validation', 'Range is conflicting ,please resolve it first.'); ?>';
                                    cnt++;
                                    validate = false;
                                }
                            }
                        }
                    }
                }
            }
        }
        if (validate) {
            if (no != '0') {
                document.getElementById(no).bgColor = '#fffff';
                oldItems[no - 1 ] = newItem;
            } else {
                oldItems.push(newItem);
            }
            localStorage.setItem('transactionsArray', JSON.stringify(oldItems));
            bindvalue();
            $('#error-summary ul').html('');
            $('#error-summary').hide();
            $('#transaction_code').val('0');
            $('#edit_tr').val('0');
            $('#tblheadloadtransaction-from_km').val('');
            $('#tblheadloadtransaction-to_km').val('');
            $('#tblheadloadtransaction-from_qty').val('');
            $('#tblheadloadtransaction-to_qty').val('');
            $('#tblheadloadtransaction-value').val('');
            $('#tblheadloadtransaction-km_value').val('');
            $("#addbutton").text('<?php echo Yii::t('app', 'Add Transaction') ?>');
            $('#tblheadloadtransaction-from_km').focus();
            var success = '';
            if (no != 0) {
                success = "<?php echo Yii::t('app', 'Record Updated Successfully'); ?>";
            } else {
                success = "<?php echo Yii::t('app', 'Record Added Successfully'); ?>";
            }
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>" + success + "</span></div></div>");

//            bootbox.alert('<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-info-circle text-primary\'></i></div><div class=\'col-sm-10 padding-left-0\'>' + success + '</div></div>');
            // alert(success);
        } else {
            $('#error-summary ul').html('');
            for (j = 0; j < message.length; j++) {
                $('#error-summary ul').append('<li>' + message[j] + '</li>');
            }
            $('#error-summary').show();
        }
    }
    function EditData(cnt) {
        var local = JSON.parse(localStorage.getItem('transactionsArray'));
        for (i = 0; i < local.length; i++) {
            var c = i + 1;
            document.getElementById(c).bgColor = '#fffff';
        }
        var local = JSON.parse(localStorage.getItem('transactionsArray'))[cnt - 1];
        if (local) {
            $("#addbutton").text('<?php echo Yii::t('app', 'Update Transaction') ?>');
            document.getElementById(cnt).bgColor = '#c8cace';
            $('#transaction_code').val(local['head_load_transaction_code']);
            $('#edit_tr').val(cnt);
            $('#tblheadloadtransaction-from_km').val(local['from_km']);
            $('#tblheadloadtransaction-to_km').val(local['to_km']);
            $('#tblheadloadtransaction-from_qty').val(local['from_qty']);
            $('#tblheadloadtransaction-to_qty').val(local['to_qty']);
            $('#tblheadloadtransaction-value').val(local['value']);
            $('#tblheadloadtransaction-km_value').val(local['km_value']);
        }
    }
    function DeleteData(cnt) {
        bootbox.confirm({
            message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question-circle\'></i></div><span><?php echo Yii::t("app", "Are you sure to delete this transaction ?") ?></span></div></div>',
            callback: function (result) {
                if (result) {
                    var local = JSON.parse(localStorage.getItem('transactionsArray'))[cnt - 1];
                    if (local) {
                        var code = local['head_load_transaction_code'];
                        if (code != '0') {
                            $.ajax({
                                type: 'post',
                                url: '<?php echo Url::to(['transaction-delete']); ?>',
                                data: 'id=' + code,
                                success: function (data) {
                                    var obj1 = $.parseJSON(data);
                                    if (obj1.status == 'success')
                                    {
                                        var oldItems = JSON.parse(localStorage.getItem('transactionsArray')) || [];
                                        oldItems.splice(cnt - 1, 1);
                                        localStorage.setItem('transactionsArray', JSON.stringify(oldItems));
                                        bindvalue();
                                    }
                                }
                            });
                        } else {
                            var oldItems = JSON.parse(localStorage.getItem('transactionsArray')) || [];
                            oldItems.splice(cnt - 1, 1);
                            localStorage.setItem('transactionsArray', JSON.stringify(oldItems));
                            bindvalue();
                        }
                    }
                }
            }
        });
    }
    function bindvalue() {
        var local = JSON.parse(localStorage.getItem('transactionsArray'));
        var body = '';
        for (i = 0; i < local.length; i++) {
            var cnt = i + 1;
            local[i]['record'] = cnt;
            body += '<tr id=' + cnt + '>';
            body += '<td>' + cnt + '</td>';
            body += '<td style=display:none>' + local[i]['head_load_transaction_code'] + '</td>';
            body += '<td>' + local[i]['from_km'] + '</td>';
            body += '<td>' + local[i]['to_km'] + '</td>';
            body += '<td>' + local[i]['from_qty'] + '</td>';
            body += '<td>' + local[i]['to_qty'] + '</td>';
            body += '<td>' + local[i]['km_value'] + '</td>';
            body += '<td>' + local[i]['value'] + '</td>';
            var icon = '<span class=\'glyphicon glyphicon-pencil\'></span>';
            var icondlt = '<span class=\'glyphicon glyphicon-trash\'></span>';
            var button = '<a href="#" title="<?php echo Yii::t('app', 'Edit') ?>" onclick=EditData(' + cnt + ')>' + icon + '</a><a href="#" title="<?php echo Yii::t('app', 'Delete') ?>" onclick=DeleteData(' + cnt + ')>' + icondlt + '</a>';
            body += '<td class="action-icons">' + button + '</td>';
            body += '</tr>';
        }
        $('#transactions tbody').html(body);
        $('#load_transaction').val(JSON.stringify(local));
    }
</script>
<?php
$script = "
    $(document).ready(function(){
    $('#tblheadload-criteria_description').blur(function(){
       Allowadd();
    });
});
$('#head-load-form').submit(function(e) {
 var local = JSON.parse(localStorage.getItem('transactionsArray'));
 if(local.length <= 0){
   e.preventDefault();
   //alert ('Please add transaction details.');
   bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please add transaction details.</span></div></div>');
//   bootbox.alert('<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-times-circle text-info\'></i></div><div class=\'col-sm-10 padding-left-0\'>" . Yii::t('app', 'Please add transaction details.') . "</div></div>');
  }else{
    $('#headdiv :input').attr('disabled', false);
  }
  
});
  $('#trdiv :input').attr('disabled', true);
  localStorage.removeItem('transactionsArray');
  var jsonEncoded = JSON.parse('" . $jsonEncoded . "');
  var Items = JSON.parse(localStorage.getItem('transactionsArray')) || [];
  for(i=0;i<jsonEncoded.length;i++){
  jsonEncoded[i]['record']=i+1;
    Items.push(jsonEncoded[i]);
}
  localStorage.setItem('transactionsArray', JSON.stringify(Items));  
  bindvalue();
 ";
$this->registerJs($script, View::POS_END, 'head_load');
?>