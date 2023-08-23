<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$this->title = Yii::$app->label->title('create', 'Outward/In-Use Asset');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['options' => [
                        'id' => 'transaction_form',
                        'field-class' => 'form-group col-sm-6'
                    ],
                    'validateOnBlur' => FALSE,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>   
        <div class="single_entry_area col-sm-12 padding-left-0 padding-right-0">
            <div class="col-sm-12 padding-left-0 padding-right-0">
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('store_location_type', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('from_type'), false, 'from_type'); ?>
                </div>
                <div class="col-sm-2 from_4 from_1 default_hide from_hide">
                    <?= Yii::$app->dropdown->depend_dropdown('slc_type', $model, $form, 'tblassettransaction-from_type', '', $model->getAttributeLabel('from_dest'), 'from_dest', false); ?>
                </div>
                <div class="col-sm-2 from_2 from_3 default_hide from_hide">
                    <?= Yii::$app->dropdown->union_plant($model, $form, 'tblassettransaction-union_code', 'from_plant', $model->getAttributeLabel('from_plant')); ?>
                </div>
                <div class="col-sm-2 from_2 from_3 default_hide from_hide">
                    <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblassettransaction-from_plant', 'from_mcc', $model->getAttributeLabel('from_mcc')); ?>
                </div>
                <div class="col-sm-2 from_2 from_3 default_hide from_hide">
                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblassettransaction-from_mcc', 'from_bmc', $model->getAttributeLabel('from_bmc')); ?>
                </div>
                <div class="col-sm-2 from_3 default_hide from_hide">
                    <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblassettransaction-from_bmc', 'from_dcs', $model->getAttributeLabel('from_dcs'), FALSE, '', FALSE, TRUE); ?>
                </div>
            </div>
            <div class="col-sm-12 padding-left-0 padding-right-0">
                <div class="col-sm-2">
                    <?= Yii::$app->dropdown->dropdown('store_location_type', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('to_type'), false, 'to_type'); ?>
                </div>
                <div class="col-sm-2 to_4 to_1 default_hide to_hide">
                    <?= Yii::$app->dropdown->depend_dropdown('slc_type', $model, $form, 'tblassettransaction-to_type', '', $model->getAttributeLabel('to_dest'), 'to_dest', false); ?>
                </div>
                <div class="col-sm-2 to_2 to_3 default_hide to_hide disa_drop">
                    <?= Yii::$app->dropdown->union_plant($model, $form, 'tblassettransaction-union_code', 'to_plant', $model->getAttributeLabel('to_plant')); ?>
                </div>
                <div class="col-sm-2 to_2 to_3 default_hide to_hide disa_drop">
                    <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblassettransaction-to_plant', 'to_mcc', $model->getAttributeLabel('to_mcc')); ?>
                </div>
                <div class="col-sm-2 to_2 to_3 default_hide to_hide disa_drop">
                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblassettransaction-to_mcc', 'to_bmc', $model->getAttributeLabel('to_bmc')); ?>
                </div>
                <div class="col-sm-2 to_3 default_hide to_hide">
                    <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblassettransaction-to_bmc', 'to_dcs', $model->getAttributeLabel('to_dcs'), FALSE, '', FALSE, TRUE); ?>
                </div>
                <?php // Html::activeHiddenInput($model, 'to_type', ['id' => 'to_type']) ?>
            </div>
            <!--<div class="col-sm-6">-->
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'transaction_date', '', false, false, false); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'in_ward', ['checkboxTemplate' => "<div class='checkbox mt25'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox(); ?>
            </div> 
            <div class="col-sm-4">
                <?= $form->field($model, 'remarks')->textInput() ?>
            </div>
            <!--</div>-->
        </div>
        <div class="col-sm-12 padding-left-0 padding-right-0">
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', FALSE, FALSE); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->depend_dropdown('union_asset', $model, $form, 'tblassettransaction-union_code', '', $model->getAttributeLabel('asset_code'), 'asset_code', FALSE); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'qty')->textInput() ?>
            </div>
            <!--<div class="disp_none">-->
            <div class="disp_none">
                <div class="col-sm-1">
                    <?= $form->field($model, 'is_serial_number')->textInput()->label(false) ?>
                </div>
                <div class="col-sm-1">
                    <?= $form->field($model, 'selected_sr_no')->textInput()->label(false) ?>
                    <?= Html::textInput('added_serial_no', 0, ['id' => 'added_serial_no']); ?>
                </div>
            </div>
            <div class="col-sm-4 mt20">
                <?=
                Html::a(Yii::t('app', 'Add'), 'javascript:void(0)', ['class' => 'btn btn-primary add-asset-record disabled no_pointer'])
                ?>
                <?php
//                Html::a(Yii::t('app', 'Add Sr. No.'), 'javascript:void(0)', ['class' => 'btn btn-primary add-serial-record disabled no_pointer'])
                ?>
            </div>
        </div>
        <div class="col-sm-12">
            <table class="table table-bordered table-striped table-main table-language br_grey bl_grey asset_transaction_table">
                <thead>
                    <tr>
                        <th><?= $model->getAttributeLabel('asset_code') ?></th>
                        <th><?= $model->getAttributeLabel('asset_name') ?></th>
                        <th><?= $model->getAttributeLabel('serial_number') ?></th>
                        <th><?= $model->getAttributeLabel('qty') ?></th>
                        <th><?= Yii::t('app', 'Action') ?></th>
                    </tr> 
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="col-sm-12 mt25 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?php
                AjaxSubmitButton::begin([
                    'label' => Yii::t('app', 'Save'),
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => Url::to(['asset-transaction']),
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
                                                                     $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                     bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
                                                                }
                                                 }'),
                    ],
                    'options' => ['class' => 'btn btn-default btn-save-txn disabled no_pointer',
                        'type' => 'submit'],
                ]);
                AjaxSubmitButton::end();
                ?>
                <?= Yii::$app->controls->custombutton(Yii::t('app', ucfirst('reset')), 'asset-transaction'); ?>
                <?= Yii::$app->controls->cancel($model); ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-12 transaction_section">
    <div class="modal modal-default fade" id="select_serial_nos" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo 'Asset Serial No'; ?></h4>
                </div>
                <div class="">
                    <div id='select_serial_no_area'>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$script = "
    $('#tblassettransaction-from_type').on('change', function(){
        addBtnEnable();
        showHideParams('from');
        getStoreLocationCode('from', 'Yes');
//        checkInWard();
    });
    $('#tblassettransaction-from_dest').on('change', function(){
        addBtnEnable();
//        checkInWard();
    });
    $('#tblassettransaction-to_type').on('change', function(){
        addBtnEnable();
        showHideParams('to');
        getStoreLocationCode('to', 'Yes');
        setDcsData();
    });
    $('#tblassettransaction-to_dest').on('change', function(){
        addBtnEnable();
    });
    $('#tblassettransaction-transaction_date').on('change', function(){
        addBtnEnable();
    });
    $('#tblassettransaction-asset_code').on('change', function(){
        addBtnEnable();
    });
    $('#tblassettransaction-qty').on('change', function(){
        addBtnEnable();
    });
   
    function addBtnEnable(){
        var from_type = $('#tblassettransaction-from_type').val();
        var from_dest = $('#tblassettransaction-from_dest').val();
        var to_type = $('#tblassettransaction-to_type').val();
        var to_dest = $('#tblassettransaction-to_dest').val();
        var transaction_date = $('#tblassettransaction-transaction_date').val();
        var asset_code = $('#tblassettransaction-asset_code').val();
        var qty = $('#tblassettransaction-qty').val();
        if(from_type != '' && from_dest != '' && to_type != '' && to_dest != '' && transaction_date != '' && asset_code != '' && qty != '') {
            $('.add-asset-record').removeClass('disabled no_pointer');
        } else {
            $('.add-asset-record').addClass('disabled no_pointer');
//            $('.add-serial-record').addClass('disabled no_pointer');
        }
    }
    
    $('.add-asset-record').on('click', function(){
        var from_type = $('#tblassettransaction-from_type').val();
        var to_type = $('#tblassettransaction-to_type').val();
        var from_bmc = $('#tblassettransaction-from_bmc').val();
        var to_bmc = $('#tblassettransaction-to_bmc').val();
        var from_dest = $('#tblassettransaction-from_dest').val();
        var asset_code = $('#tblassettransaction-asset_code').val();
        var qty = $('#tblassettransaction-qty').val();
        var check_box_id = $('.serial_no_checkbox:checked').attr('id');      
        var serial_no = '';
        if(check_box_id != undefined){
            var serailNumber = check_box_id.split('===');
            serial_no = serailNumber[0]
        }
        if(asset_code != ''){
            var existData = $('.selected_'+asset_code).not('.edit_asset').text().length;
            if(parseInt(existData) > 0){
                var msg = '" . Yii::t('app', 'Asset already added') . "';
                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+msg+\"</span></div></div>\");
                return false;
            }
        }
        var assetName = $('#tblassettransaction-asset_code option:selected').text();
        var to_dest = $('#tblassettransaction-to_dest').val();
        var in_ward = $('#tblassettransaction-in_ward').is(':checked');
        var alert_msg = '';
        if(in_ward) {
            if(from_dest != to_dest) {
                alert_msg = '" . Yii::t('app', 'From Dest and To Dest must be Same for In Use Transaction.') . "';
            }
        } else {            
            if(from_dest == to_dest) {
                alert_msg = '" . Yii::t('app', 'From Dest and To Dest must be different for Outward Transaction.') . "';
            }
        }
        if(alert_msg != '') {
            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+alert_msg+\"</span></div></div>\");
            return false;
        }

        if(from_type != '' && from_dest != '' && asset_code != '' && qty != ''){
            $.ajax({
                type: 'post',
                url: '" . Url::to(['validate-asset-qty']) . "',
                data: {'from_type' : from_type, 'from_dest' : from_dest, 'asset_code' : asset_code, 'qty' : qty, 'to_type' : to_type, 'to_bmc' : to_bmc, 'from_bmc' : from_bmc},
                success: function(data) {
                    var obj1 = $.parseJSON(data);
                    var append_raw = 0;
                    if(obj1.status == 'success') {
                        $('#tblassettransaction-is_serial_number').val(obj1.is_serial_number);
                        if(obj1.is_serial_number == 1){
//                            $('.add-serial-record').removeClass('disabled no_pointer');
                            var selected_sr_no = $('#tblassettransaction-selected_sr_no').val();
                            var added_sr_no = $('#added_serial_no').val();
                            if(selected_sr_no == '' || added_sr_no != qty){
                                serialNoForm();
                            } else {
                                append_raw = 1;
                            }
                        } else {
//                            $('.add-serial-record').addClass('disabled no_pointer');
                            append_raw = 1;
                        }
                        if(append_raw == 1) {
                            var add_row = '';
                            var add_class = 'test';
                            if(obj1.is_serial_number != 1){
                                add_class = 'disabled';
                            }
                            add_row += '<tr class=\"selected_'+asset_code+'\">';
                            add_row += '<td>' + asset_code + '<input type=\"hidden\" class=\"added_selected_sr_no\" value=\''+selected_sr_no+'\' name=\"TblAssetTransaction[selected_sr_no]['+asset_code+'][serial_number]\" ><input type=\"hidden\" class=\"added_is_serial_number\" value=\"'+obj1.is_serial_number+'\" name=\"TblAssetTransaction[selected_sr_no]['+asset_code+'][is_serial_number]\" ><input type=\"hidden\" class=\"added_asset_code\" value=\"'+asset_code+'\" name=\"TblAssetTransaction[selected_sr_no]['+asset_code+'][asset_code]\" ></td>';
                            add_row += '<td class=\"asset_name\">' + assetName + '</td>';
                            add_row += '<td class=\"serail_number\">' + serial_no + '</td>';                            
                            add_row += '<td>' + qty + '<input type=\"hidden\" class=\"added_qty\" value=\"'+qty+'\" name=\"TblAssetTransaction[selected_sr_no]['+asset_code+'][qty]\" ></td>';
                            add_row += '<td><a href=\'javascript:void(0)\' onClick=\'editTransaction(\"'+asset_code+'\")\' class=\'edit\' title=\'Edit\'><span class=\"fa fa-pencil\"></span></a><a href=\'javascript:void(0)\' onClick=\'viewTransaction(\"'+asset_code+'\")\' class=\'view ml15 ' + add_class + '\' title=\'View\'><span class=\"fa fa-eye\"></span></a><a href=\'javascript:void(0)\' onClick=\'deleteTransaction(\"'+asset_code+'\")\' class=\'view ml15\' title=\'Delete\'><span class=\"fa fa-remove\"></span></a></td>';
                            add_row += '</tr>';
                            $('tbody').append(add_row);
                            $('#tblassettransaction-asset_code').val('');
                            $('#tblassettransaction-asset_code').trigger('change');
                            $('#tblassettransaction-asset_code').trigger('select2:select');
                            $('#tblassettransaction-is_serial_number').val('');
                            $('#tblassettransaction-selected_sr_no').val('');
                            $('#tblassettransaction-qty').val('');
                            $('#added_serial_no').val('');
                            $('tbody tr.edit_asset').remove();
                            $('.single_entry_area').addClass('disabled no_pointer');
                            $('.btn-save-txn').removeClass('disabled no_pointer');
                        }
                    } else {
//                        $('.add-serial-record').addClass('disabled no_pointer');
                        bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                    }
                },
            });
        }
    });
    
//    $('.add-serial-record').on('click', function(){
//        serialNoForm();
//    });
    
    function serialNoForm(){
        var from_type = $('#tblassettransaction-from_type').val();
        var from_dest = $('#tblassettransaction-from_dest').val();
        var asset_code = $('#tblassettransaction-asset_code').val();
        var qty = $('#tblassettransaction-qty').val();
        var selected_sr_no = $('#tblassettransaction-selected_sr_no').val();
        if(from_type != '' && from_dest != '' && asset_code != '' && qty != ''){
            $.ajax({
                type: 'post',
                url: '" . Url::to(['get-serial-no']) . "',
                data: {'from_type' : from_type, 'from_dest' : from_dest, 'asset_code' : asset_code, 'qty' : qty, 'selected_sr_no' : selected_sr_no},
                success: function(data) {
                    $('#select_serial_no_area').html(data);
                    $('#select_serial_nos').modal('toggle');
                },
            });
        }
    }
    $(document).on('change', '#select_all', function () {
        $('.serial_no_checkbox').prop('checked', $(this).prop('checked'));
    });

    $(document).on('click', '.add_serial_no', function () {
        var checked_length = $('.serial_no_checkbox:checked').length;
        var check_length = parseFloat($('#serial_no_count').val());
        if(isNaN(checked_length)){
            checked_length = 0;
        }
        if(isNaN(check_length)){
            check_length = 0;
        }
        if(checked_length != check_length){
            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>" . Yii::t('app', 'Please select') . " \"+check_length+\" " . Yii::t('app', 'serial no.') . "</span></div></div>\");
        } else {
            var select_array = [];
            $('.serial_no_checkbox:checked').each(function(){
                select_array.push($(this).val());
            });
            $('#tblassettransaction-selected_sr_no').val(JSON.stringify(select_array));
            $('#added_serial_no').val(checked_length);
            $('#select_serial_nos').modal('toggle');
            $('.add-asset-record').trigger('click');
        }
    });
    
    function editTransaction(asset_code) {
        $('tbody tr').removeClass('edit_asset');
        var select_raw_class = 'selected_'+asset_code;
        $('.'+select_raw_class).addClass('edit_asset');
        $('#tblassettransaction-asset_code').val($('.'+select_raw_class+' .added_asset_code').val());
        $('#tblassettransaction-asset_code').trigger('change');
        $('#tblassettransaction-asset_code').trigger('select2:select');
        $('#tblassettransaction-qty').val($('.'+select_raw_class+' .added_qty').val());
        $('#tblassettransaction-is_serial_number').val($('.'+select_raw_class+' .added_is_serial_number').val());
        $('#tblassettransaction-selected_sr_no').val($('.'+select_raw_class+' .added_selected_sr_no').val());
        $('#added_serial_no').val($('.'+select_raw_class+' .added_qty').val());
        $('.add-asset-record').removeClass('disabled no_pointer');
        $('#added_serial_no').val('');
//        if($('#tblassettransaction-is_serial_number').val() == 1) {
//            $('.add-serial-record').removeClass('disabled no_pointer');
//        }
    }
    
    function deleteTransaction(asset_code) {
        var assetName = $('.selected_'+asset_code+' .asset_name').text();
        bootbox.confirm(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-info\'></i></div><span>" . Yii::t('app', 'Are you sure you want to remove ') . "\"+assetName+\".</span></div></div>\", 
        function(result){                   
         if(result){
                $('.selected_'+asset_code).remove();
                var rowCount = $('tbody tr').length;
                if(rowCount == 0){
                    $('.btn-save-txn').addClass('disabled no_pointer');
                }
         }});   
    }
    
    function viewTransaction(asset_code){
        var tr_class = 'selected_'+asset_code;
        var selectedSerialNo = $('.'+tr_class +' .added_selected_sr_no').val();
        
        var record = '<div class=\"modal-body\">';
        record += '<div>';
        record += '" . Yii::t('app', 'No Data Available') . "';
        record += '</div></div>';
        if(selectedSerialNo != '') {
            var final_json = $.parseJSON(selectedSerialNo);
            record = '<div class=\"modal-body\">';
            record += '<div><table class=\"table table-bordered table-striped table-main table-language br_grey bl_grey asset_transaction_table\"><thead><tr><th>" . Yii::t('app', 'Serial No.') . "</th></tr> </thead><tbody>';

            $.each(final_json, function(index, value) {
                var serial_no = value.split('===');
                record += '<tr><td>'+serial_no[0]+'</td></tr>';
            });
            record += '</tbody></table></div></div>';
        }
        
        record += '<div class=\"modal-footer\">';
        record += '<button type=\"button\" class=\"btn btn-danger close-import\" data-dismiss=\"modal\">" . Yii::t('app', 'Cancel') . "</button>';
        record += '</div>'; 
        $('#select_serial_no_area').html(record);
        $('#select_serial_nos').modal('toggle');   
    }
    $('#tblassettransaction-in_ward').click(function(){
//        checkInWard();
    });
    
    function checkInWard(){
        if($('#tblassettransaction-in_ward').is(':checked')) {
            $('#tblassettransaction-to_type').parent('div').addClass('disabled');
            $('#tblassettransaction-to_dest').parent('div').addClass('disabled');
            var fromType = $('#tblassettransaction-from_type').val();
            if(fromType != '') {
                $('#tblassettransaction-to_type').val(fromType);
                $('#tblassettransaction-to_type').trigger('change');
                $('#tblassettransaction-to_type').trigger('select2:select');
                var fromTest = $('#tblassettransaction-from_dest').val();
                if(fromTest != '') {
                    $('#tblassettransaction-to_dest').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                        $('#tblassettransaction-to_dest').val(fromTest);
                        $('#tblassettransaction-to_dest').trigger('change');
                        $('#tblassettransaction-to_dest').trigger('select2:select');
                    });
                }
            }
        } else {
            $('#tblassettransaction-to_type').parent('div').removeClass('disabled');
            $('#tblassettransaction-to_dest').parent('div').removeClass('disabled');
        }
    }
    
    $('.default_hide').hide();
    function showHideParams(type) {
        var selectParam = $('#tblassettransaction-'+type+'_type').val();
        $('.'+type+'_hide').hide();
        $('.'+type+'_'+selectParam).show();
    }
    
    $('#tblassettransaction-from_bmc').on('change', function(){
        var selectedParamName = getParamName('from');
        if(selectedParamName == '2'){
            getStoreLocationCode('from');
        }
    });
    $('#tblassettransaction-from_dcs').on('change', function(){
        var selectedParamName = getParamName('from');
        if(selectedParamName == '3'){
            getStoreLocationCode('from');
        }
    });
    $('#tblassettransaction-to_bmc').on('change', function(){
        var selectedParamName = getParamName('to');
        if(selectedParamName == '2'){
            getStoreLocationCode('to');
        }
    });
    $('#tblassettransaction-to_dcs').on('change', function(){
        var selectedParamName = getParamName('to');
        if(selectedParamName == '3'){
            getStoreLocationCode('to');
        }
    });
    function getParamName(select_type){
        var selectParam = $('#tblassettransaction-'+select_type+'_type').val();
        return selectParam;
    }
    function getStoreLocationCode(select_type, checkEvent = 'No'){
        var selectParam = $('#tblassettransaction-'+select_type+'_type').val();
        if(selectParam == '2' || selectParam == '3'){
            var paramName = selectParam == '3' ? 'dcs' : 'bmc';
            mainVal = $('#tblassettransaction-'+select_type+'_'+paramName).val();
            var dest_type_code = $('#tblassettransaction-'+select_type+'_type').val();
            if(mainVal != '' && mainVal != null && mainVal != undefined && dest_type_code != '') {
                var dcs_code = $('#tblassettransaction-'+select_type+'_dcs').val();
                var bmc_code = $('#tblassettransaction-'+select_type+'_bmc').val();
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/assetmanagement/tbl-store-location/get-store-location-code']) . "',
                    data: {'dest_type_code' : dest_type_code, 'dest_type' : paramName, 'bmc_code' : bmc_code, 'dcs_code' : dcs_code},
                    success: function(data) {
                        var obj1 = $.parseJSON(data);
                        if(obj1.status == 'success') {
                            if(checkEvent == 'Yes'){
                                $('#tblassettransaction-'+select_type+'_dest').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                                    $('#tblassettransaction-'+select_type+'_dest').val(obj1.sloc_code);
                                    $('#tblassettransaction-'+select_type+'_dest').trigger('change');
                                    $('#tblassettransaction-'+select_type+'_dest').trigger('select2:select');
                                });
                            } else {
                                $('#tblassettransaction-'+select_type+'_dest').val(obj1.sloc_code);
                                $('#tblassettransaction-'+select_type+'_dest').trigger('change');
                                $('#tblassettransaction-'+select_type+'_dest').trigger('select2:select');
                            }
                        } else {
                            $('#tblassettransaction-'+select_type+'_dest').val('');
                            $('#tblassettransaction-'+select_type+'_dest').trigger('change');
                            $('#tblassettransaction-'+select_type+'_dest').trigger('select2:select');
                            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                        }
                    },
                });
            }
        } else {
            $('#'+select_type+'_hide select').val('');
            $('#'+select_type+'_hide select').trigger('change');
            $('#'+select_type+'_hide select').trigger('select2:select');
        }
    }
    
    $('#tblassettransaction-from_dest').on('change', function(){
        $('#tblassettransaction-to_type').val('');
        $('#tblassettransaction-to_type').trigger('change');
    });
     function setDcsData() {
        var selectParam = $('#tblassettransaction-from_type').val();
         var fromType = $('#tblassettransaction-from_type').val();
         var fromPlant = $('#tblassettransaction-from_plant').val();
         var fromMcc = $('#tblassettransaction-from_mcc').val();
         var fromBmc = $('#tblassettransaction-from_bmc').val();
         var fromDcs = $('#tblassettransaction-from_dcs').val();
         $('.disa_drop').removeClass('disabled');
        var selectToParams = $('#tblassettransaction-to_type ').val();
        if(selectParam == '3'){
            if(selectToParams == '3' || selectToParams == '2'){
                $('.disa_drop').addClass('disabled');
                $('#tblassettransaction-to_plant').val(fromPlant);
                $('#tblassettransaction-to_plant').trigger('change');
                $('#tblassettransaction-to_plant').trigger('select2:select');
                $('#tblassettransaction-to_mcc').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                $('#tblassettransaction-to_mcc').val(fromMcc);
                $('#tblassettransaction-to_mcc').trigger('change');
                $('#tblassettransaction-to_mcc').trigger('select2:select');
              });
              $('#tblassettransaction-to_bmc').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                $('#tblassettransaction-to_bmc').val(fromBmc);
                $('#tblassettransaction-to_bmc').trigger('change');
                $('#tblassettransaction-to_bmc').trigger('select2:select');
              });             
 
            }
        }else if(selectParam == '2'){
            if(selectToParams == '3'){
               $('#tblassettransaction-to_plant').val(fromPlant);
               $('#tblassettransaction-to_plant').trigger('change');
               $('#tblassettransaction-to_plant').trigger('select2:select');
                  $('#tblassettransaction-to_mcc').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                     $('#tblassettransaction-to_mcc').val(fromMcc);
                     $('#tblassettransaction-to_mcc').trigger('change');
                     $('#tblassettransaction-to_mcc').trigger('select2:select');
                   });
                    $('#tblassettransaction-to_bmc').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                $('#tblassettransaction-to_bmc').val(fromBmc);
                $('#tblassettransaction-to_bmc').trigger('change');
                $('#tblassettransaction-to_bmc').trigger('select2:select');
              });
                    $('.disa_drop').addClass('disabled');
            }
        }
        
    }
      
";
$this->registerJs($script, View::POS_END, 'create-asset-transaction');
?>