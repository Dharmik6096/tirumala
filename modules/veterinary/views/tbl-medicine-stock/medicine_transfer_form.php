<?php

use app\components\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\web\View;

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'medicine-transfer-form'],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
echo $form->errorSummary($txModel);
?>
<?php if (isset($searchModel)) : ?>
    <?= Html::hiddenInput('TblMedicineStockTransferTxnSearch[union_code]', $searchModel->union_code) ?>
    <?= Html::hiddenInput('TblMedicineStockTransferTxnSearch[transaction_date]', $searchModel->transaction_date) ?>
    <?= Html::hiddenInput('TblMedicineStockTransferTxnSearch[remarks]', $searchModel->remarks) ?>
    <?= Html::hiddenInput('TblMedicineStockTransferTxnSearch[from_user_code]', $searchModel->from_user_code) ?>
    <?= Html::hiddenInput('TblMedicineStockTransferTxnSearch[to_user_code]', $searchModel->to_user_code) ?>
    <?= Html::hiddenInput('TblMedicineStockTransferTxnSearch[medicine_wise]', $searchModel->medicine_wise) ?>
<?php endif; ?>
<div class="row table_form theme-box theme_border_right theme_border_left theme_border_bottom">
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Medicine Stock Txn Transfer Details</h4>
        </div>
        <?= $form->field($model, 'medicine_stock_transfer_code')->hiddenInput()->label(FALSE) ?>
        <?= Html::activeHiddenInput($txModel, 'expire_date'); ?>
        <div class="col-sm-3 reset_field">
            <?php Yii::$app->dropdown->depend_dropdown('medicine_master', $txModel, $form, 'tblmedicinestocktransfertxnsearch-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $txModel->getAttributeLabel('medicine_id'), 'medicine_id'); ?>
        </div>
        <div class="col-sm-2 reset_field">
            <?= Yii::$app->dropdown->medicineBatch($txModel, $form, 'tblmedicinestocktransfertxnsearch-union_code,tblmedicinestocktransfertxn-medicine_id,tblmedicinestocktransfertxnsearch-from_user_code', 'batch_no', $txModel->getAttributeLabel('batch_no'), FALSE); ?>
        </div>
        <div class="col-sm-1 create_fields reset_field">
            <?= $form->field($txModel, 'available_stock')->textInput(['readonly' => TRUE])->label(Yii::t('app', 'Available Stock')) ?>
        </div>
        <div class="col-sm-2 create_fields reset_field number-validate">
            <?= $form->field($txModel, 'qty')->textInput()->label(Yii::t('app', 'Quantity')) ?>
        </div>

        <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?php
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'SAVE'),
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['create']),
                        'beforeSend' => new JsExpression("function(data){
                            $('#loadercontent').show();
                            $('#pageloader').show();
                        }"),
                        'success' => new JsExpression("function(data){
                            var data=$.parseJSON(data);
                            $('#loadercontent').hide();
                            $('#pageloader').hide();
                            if (data.status == 'success'){
                                $('#loadercontent').hide();
                                $('#pageloader').hide();
                                $('.help-block').text('');
                                $('.form-group').removeClass('has-error');
                                $('.create_fields').removeClass('disabled');
                                $('.DisableAferAdd').addClass('disabledDiv');
                                $('.error-summary').hide();
                                $('.error-summary li').remove();
                                $('#tblmedicinestocktransfer-medicine_stock_transfer_code').val(data.pk_code);
                                reloadGrid(data.pk_code);
                                $('#medicine-transfer-form .reset_field input').val('');
                                $('#medicine-transfer-form .reset_field select').val('');
                                $('#medicine-transfer-form .reset_field textarea').val('');
                                $('#tblmedicinestocktransfertxn-medicine_id').val(null).trigger('change');
                                $('#tblmedicinestocktransfertxn-batch_no').val(null).trigger('change');
                                $('.panel-body').scrollTop(0);
                                bootbox.alert('<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-info\"><i class=\"fa fa-info\"></i></div><span>'+data.msg+'</span></div></div>', function(result){
                                    reloadGrid(data.pk_code);
                                });
                            }else{
                                $('#loadercontent').hide();
                                $('#pageloader').hide();
                                var cnt=0;
                                $.each(data, function(key, val) {
                                    var id = 'tblmedicinestocktransfertxn-' + key;
                                    var parent_div = $('#'+id).closest('.form-group');
                                    parent_div.find('.help-block').text(val[0]);
                                    parent_div.addClass('has-error');
                                });
                            }
                        }"),
                    ],
                    'options' => [
                        'class' => 'btn btn-default btn-raised',
                        'type' => 'submit'
                    ],
                ]);
                AjaxSubmitButton::end();
                ?>
                <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
function setData(field = ''){
    if(field != '' && field != null && field != undefined && field != 'Loading ...'){
        return true;
    }else{
        return false;
    }
}

function getAvailableStock(){
        var union = $('#tblmedicinestocktransfertxnsearch-union_code').val();
        var from_user_code = $('#tblmedicinestocktransfertxnsearch-from_user_code').val();
        var medicineId = $('#tblmedicinestocktransfertxn-medicine_id').val();
        var batch_no = $('#tblmedicinestocktransfertxn-batch_no').val();

        if(setData(union) && setData(from_user_code) && setData(medicineId) && setData(batch_no)){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['get-available-stock']) . "',
                data: {'union':union,'from_user_code':from_user_code,'medicineId':medicineId,'batch_no':batch_no},
                success: function(data) {
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success') {
                        $('#tblmedicinestocktransfertxn-available_stock').val(obj.stock);
                        $('#tblmedicinestocktransfertxn-expire_date').val(obj.expireDate);
                    }
                },
                error:function(data){
                }
            });
        } else {
            $('#tblmedicinestocktransfertxn-available_stock').val('');
            $('#tblmedicinestocktransfertxn-expire_date').val('');
        }

    }

$('#tblmedicinestocktransfertxnsearch-union_code').on('change', function(){ getAvailableStock(); });
$('#tblmedicinestocktransfertxnsearch-from_user_code').on('change', function(){ getAvailableStock(); });
$('#tblmedicinestocktransfertxn-medicine_id').on('change', function(){ getAvailableStock(); });
$('#tblmedicinestocktransfertxn-batch_no').on('change', function(){ getAvailableStock(); });
$('#tblmedicinestocktransfertxn-batch_no').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    let varVal = $('#tblmedicinestocktransfertxn-batch_no option:nth-child(2)').val();
    if(varVal == undefined) {
        varVal = '';
    }
    $('#tblmedicinestocktransfertxn-batch_no').val(varVal);
    $('#tblmedicinestocktransfertxn-batch_no').trigger('change');
    $('#tblmedicinestocktransfertxn-batch_no').trigger('select2:select');
});

function reloadGrid(id){
    $.ajax({
        type: 'get',
        url: '" . Url::to(['/veterinary/tbl-medicine-stock/list-grid']) . "',
        data: {medicine_stock_transfer_code: id},
        beforeSend:function(data) {
            $('#loadercontent').show();
            $('#pageloader').show();
        },
        success: function(data) {
            $('.form-grid').html(data);
            $('#loadercontent').hide();
            $('#pageloader').hide();
        },
    });
}
";
$this->registerJs($script, View::POS_END, 'serial-no-hide');
?>