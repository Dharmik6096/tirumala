<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$title = Yii::$app->label->title('create', 'Product Dispatch without Requisition');
$button = Yii::$app->label->button('create');

$this->title = Yii::t('app', $title);
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">

        <div class="tbl-product-dispatch-form">
            <?php
            $form = ActiveForm::begin(['options' => [
                            'id' => 'dispatch-without-form',
                            'field-class' => 'form-group col-sm-3'
                        ], 'validateOnBlur' => FALSE,
                        
                        'validateOnChange' => FALSE,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
                        'fieldConfig' => [
                        //'labelOptions' => [ 'class' => false],
            ]]);
            ?>
            <?php echo $form->errorSummary($model, ['id' => 'error-summary']); ?>
            <div class="row theme_border_left theme_border_right theme_border_bottom" id="headdiv">
                <div class="col-md-12 padding_10_0 theme-box ">
                    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                        <h4 class="theme-box-heading">Dispatch Details</h4>
                    </div>
                    <?= $form->field($model, 'challan_date', ['options' => ['class' => 'form-group col-sm-1']])->textInput(['maxlength' => true, 'readonly' => 'readonly', 'value' => date('d-m-Y')]) ?>                      

                    <?= $form->field($model, 'reference_no', ['options' => ['class' => 'form-group col-sm-1']])->textInput(['maxlength' => true, 'autofocus' => 'autofocus']) ?>      
                    <div class="col-sm-2" id="union">
                        <?= Yii::$app->dropdown->vehicle($model, $form, 'vehicle_no', $model->getAttributeLabel('vehicle_no'), false); ?>
                    </div>
                    <div class="col-sm-2" id="union">
                        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblproductdispatch-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
                    </div> 
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblproductdispatch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
                    </div>  
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblproductdispatch-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
                    </div>

                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->all_routes($model, $form, 'tblproductdispatch-plant_code,tblproductdispatch-mcc_plant_code,tblproductdispatch-bmc_code', 'route_code', $model->getAttributeLabel('route_code'), FALSE); ?>
                    </div>  
                </div>
            <div class="clearfix"></div>
            <div class="row" id="trdiv">
                    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                        <h4 class="theme-box-heading">Product Details</h4>
                    </div>
                <div class="col-sm-12">
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdownStatic('requisition_type', $model, $form, 'form-group', $model->getAttributeLabel('vendor_type'), false, 'vendor_type', false); ?>
                    </div>
                    <div class="col-sm-2">
                        <?php echo Yii::$app->dropdown->route_dcs($model, $form, 'tblproductdispatch-route_code', 'dcs_code', Yii::t('app', 'DCS'), false, false); ?>
                    </div>
                    <div class="col-sm-2">
                        <?php Yii::$app->dropdown->depend_dropdown('product', $transaction, $form, 'tblproductdispatch-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product'); ?>
                    </div>
                    <?= Html::hiddenInput('transaction_code', '0', ['id' => 'transaction_code']); ?>
                    <?= Html::hiddenInput('edit_tr', '0', ['id' => 'edit_tr']); ?>
                    <?= $form->field($transaction, 'dispatch_qty', ['options' => ['class' => 'form-group col-sm-1 number-validate']])->textInput(['class' => 'form-control number-validate']) ?>
                    <?= $form->field($transaction, 'rate', ['options' => ['class' => 'form-group col-sm-1']])->textInput(['readonly' => true]) ?>
                    <?= $form->field($transaction, 'amount', ['options' => ['class' => 'form-group col-sm-1']])->textInput(['readonly' => true]) ?>
                    <?= $form->field($transaction, 'discount_amount', ['options' => ['class' => 'form-group col-sm-1 number-validate']])->textInput() ?>
                    <?= Html::hiddenInput('dispatch_transaction', '', ['id' => 'dispatch_transaction']); ?>
                    <?= Html::hiddenInput('dispatch_master', '', ['id' => 'dispatch_master']); ?>

                    <div class="col-sm-2 padding_top_20">
                        <?php echo Html::button(Yii::t('app', 'Add Product'), ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+s', 'button' => 'add', 'id' => 'addbutton']); ?>
                    </div>
                </div>
            </div>
            <div class="ex-grid">
                <div class="table-responsive kv-grid-container" style="height: 285px;">
                    <table id='transactions' class="table table-bordered table-main kv-grid-table table-hover kv-table-wrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= Yii::t('app', 'Type') ?></th>
                                <th><?= Yii::t('app', 'Code') ?></th>
                                <th><?= Yii::t('app', 'Name') ?></th>
                                <!--<th><?= Yii::t('app', 'Route') ?></th>-->
                                <th><?= Yii::t('app', 'Product') ?></th>
                                <th><?= Yii::t('app', 'Dispatch Quantity') ?></th>
                                <th><?= Yii::t('app', 'Rate') ?></th>
                                <th><?= Yii::t('app', 'Amount') ?></th>
                                <th><?= Yii::t('app', 'Discount Amount') ?></th>
                                <th class="text-center"><?= Yii::t('app', 'Action') ?></th>
                            </tr>
                        </thead>
                        <tbody id='transactionsbody'>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <?= Html::submitButton(Yii::t('app', 'Submit Dispatch'), ['class' => 'btn btn-default apply-shortcut', 'value' => 'submit', 'name' => 'submit']) ?>
                <?= Yii::$app->controls->cancel($model); ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>

    function AddTransaction() {
        var validate = true;
        var message = [];
        var cnt = 0;
        var oldItems = JSON.parse(localStorage.getItem('transactionsArray')) || [];
        var no = $('#edit_tr').val();
//        var schemeItems = JSON.parse(localStorage.getItem('productScheme'));
//        var scheme_trans_code = $('#scheme_trans_code').val();
//        if (schemeItems != null) {
//            $('#tblproductdispatchtransaction-product_scheme_code').val(schemeItems.product_scheme_code);
//            $('#scheme_trans_code').val(schemeItems.scheme_trans_code);
//        }
        var vendorCode = $('#tblproductdispatch-bmc_code').val();
        if ($('#tblproductdispatch-vendor_type').val() == 'DCS') {
            vendorCode = $('#tblproductdispatch-dcs_code').val();
        }
        var vendorName = $('#tblproductdispatch-bmc_code :selected').text();
        if ($('#tblproductdispatch-vendor_type').val() == 'DCS') {
            vendorName = $('#tblproductdispatch-dcs_code :selected').text();
        }
        var newItem = {
            'union_code': $('#tblproductdispatch-union_code').val(),
            'plant_code': $('#tblproductdispatch-plant_code').val(),
            'mcc_plant_code': $('#tblproductdispatch-mcc_plant_code').val(),
            'bmc_code': $('#tblproductdispatch-bmc_code').val(),
            'bmc_name': $('#tblproductdispatch-bmc_code :selected').text(),
            'route_code': $('#tblproductdispatch-route_code').val(),
            'route': $('#tblproductdispatch-route_code :selected').text(),
            'vendor_type': $('#tblproductdispatch-vendor_type').val(),
            'vendor_code': vendorCode,
            'vendor_name': vendorName,
            'dcs_code': $('#tblproductdispatch-dcs_code').val(),
            'dcs_name': $('#tblproductdispatch-dcs_code :selected').text(),
            'product_code': $('#tblproductdispatchtransaction-product_code').val(),
            'product_name': $('#tblproductdispatchtransaction-product_code :selected').text(),
            'dispatch_qty': $('#tblproductdispatchtransaction-dispatch_qty').val(),
            'rate': $('#tblproductdispatchtransaction-rate').val(),
            'amount': $('#tblproductdispatchtransaction-amount').val(),
            'discount_amount': $('#tblproductdispatchtransaction-discount_amount').val(),
//            'product_scheme_code': $('#tblproductmaterialdispatchtransaction-product_scheme_code').val(),
            'reference_no': $('#tblproductdispatch-reference_no').val(),
            'vehicle_no': $('#tblproductdispatch-vehicle_no').val(),
//            'scheme_type': $('#scheme_type').val(),
//            'parent_product_code': $('#tblproductmaterialdispatchtransaction-parent_product_code').val(),
//            'scheme_trans_code': $('#scheme_trans_code').val(),
        };
        if (newItem.vehicle_no == '' || newItem.reference_no == '' || newItem.vendor_code == '' || newItem.vendor_type == '' || newItem.product_code == '' || newItem.rate == '' || newItem.amount == '') {
            message[cnt] = '<?php echo Yii::t('app\validation', 'All Fields are required.'); ?>';
            cnt++;
            validate = false;
        } else {
            var vendor_code = newItem.vendor_code;
            var product_code = newItem.product_code;
            var parent_product_code = newItem.parent_product_code;
            var local = JSON.parse(localStorage.getItem('transactionsArray'));
            if (localStorage.getItem('transactionsArray')) {
                for (i = 0; i < local.length; i++) {
                    if (i != no - 1) {
                        if (local[i]['vendor_code'] == vendor_code && local[i]['product_code'] == product_code) {
                            message[cnt] = '<?php echo Yii::t('app\validation', 'Product already added for '); ?>' + newItem.vendor_name + '.';
                            cnt++;
                            validate = false;
                            $('#trdiv').find('input:text').val('');
                            $('#tblproductdispatch-vendor_type').val('');
//                            $('#tblproductdispatch-bmc_code').val('');
//                            $('#tblproductdispatch-route_code').val('');
                            $('#tblproductdispatch-dcs_code').val('');
                            $('#tblproductdispatchtransaction-product_code').val('');
                        }
                    }
                }
            }

        }

        if (validate) {
//            if (schemeItems != null) {
//                var schemeproduct = {
//                    'dcs_code': newItem.dcs_code,
//                    'sub_center_code': newItem.sub_center_code,
//                    'product_code': schemeItems.product_code,
//                    'dcs_name': newItem.dcs_name,
//                    'sub_center_name': newItem.sub_center_name,
//                    'product_name': schemeItems.product_name,
//                    'dispatch_qty': schemeItems.scheme_value,
//                    'rate': schemeItems.product_rate,
//                    'amount': schemeItems.product_amount,
//                    'discount_amount': '',
//                    'product_scheme_code': '',
//                    'scheme_type': '',
//                    'parent_product_code': newItem.product_code,
//                    'scheme_trans_code': '',
//                    'passon_to_member': schemeItems.passon_to_member,
//                };
//            }
            $('#error-summary ul').html('');

            if (no != '0') {
                document.getElementById(no).bgColor = '#fffff';
                oldItems[no - 1 ] = newItem;
//                if (schemeItems != null && newItem.parent_product_code == '' && schemeItems.scheme_trans_code != scheme_trans_code) {
//                    oldItems.push(schemeproduct);
//
//                }

                $('#tblproductdispatch-vendor_type').removeAttr("disabled");
//                $('#tblproductdispatch-bmc_code').removeAttr("disabled");
//                $('#tblproductdispatch-route_code').removeAttr("disabled");
                $('#tblproductdispatch-dcs_code').removeAttr("disabled");
                $('#tblproductdispatchtransaction-product_code').removeAttr("disabled");
//                $("#tblproductmaterialdispatchtransaction-dcs_code").removeAttr("disabled");
//                $("#tblproductmaterialdispatchtransaction-sub_center_code").removeAttr("disabled");
//                $("#tblproductmaterialdispatchtransaction-product_code").removeAttr("disabled");
            } else {
                oldItems.push(newItem);
//                if (schemeItems != null) {
//                    oldItems.push(schemeproduct);
//                }
            }
            localStorage.setItem('transactionsArray', JSON.stringify(oldItems));
            bindvalue();
            $('#error-summary ul').html('');
            $('#error-summary').hide();
            $('#transaction_code').val('0');
            $('#edit_tr').val('0');
            $("#addbutton").text('<?php echo Yii::t('app', 'Add Product') ?>');
            $('#trdiv').find('input:text').val('');
            $('#tblproductdispatch-vendor_type').val('');
//            $('#tblproductdispatch-bmc_code').val('');
//            $('#tblproductdispatch-route_code').val('');
            $('#tblproductdispatch-dcs_code').val('');
            $('#tblproductdispatchtransaction-product_code').val('');
            var success = '';
            if (no != 0) {
                success = "<?php echo Yii::t('app', 'Record Updated Successfully') ?>";
            } else {
                success = "<?php echo Yii::t('app', 'Record Added Successfully') ?>";
            }
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-3x fa-question-circle text-primary\'></i></div><span>' + success + '</span></div></div>');
            // alert(success);
        } else {
            console.log(message);
            $('#error-summary ul').html('');
            for (j = 0; j < message.length; j++) {
                $('#error-summary ul').append('<li>' + message[j] + '</li>');
            }
            $('#error-summary').show();
        }
    }
    function bindvalue() {
        var local = JSON.parse(localStorage.getItem('transactionsArray'));
        var body = '';
        if (!jQuery.isEmptyObject(local)) {
            var masterlocal = JSON.parse(localStorage.getItem('masterArray'));
            if (jQuery.isEmptyObject(masterlocal)) {
                var masterlocal = JSON.parse(localStorage.getItem('masterArray')) || [];
                var vendorCode = $('#tblproductdispatch-bmc_code').val();
                if ($('#tblproductdispatch-vendor_type').val() == 'DCS') {
                    vendorCode = $('#tblproductdispatch-dcs_code').val();
                }
                var newItem = {
                    'challan_date': $('#tblproductdispatch-challan_date').val(),
                    'reference_no': $('#tblproductdispatch-reference_no').val(),
                    'vehicle_no': $('#tblproductdispatch-vehicle_no').val(),
                    'union_code': $('#tblproductdispatch-union_code').val(),
                    'plant_code': $('#tblproductdispatch-plant_code').val(),
                    'mcc_plant_code': $('#tblproductdispatch-mcc_plant_code').val(),
                    'bmc_code': $('#tblproductdispatch-bmc_code').val(),
                    'route_code': $('#tblproductdispatch-route_code').val(),
                    'vendor_type': $('#tblproductdispatch-vendor_type').val(),
                    'dcs_code': $('#tblproductdispatch-dcs_code').val(),
                    'vendor_code': vendorCode
                };
                masterlocal.push(newItem);
                localStorage.setItem('masterArray', JSON.stringify(masterlocal));
                var masterlocal = JSON.parse(localStorage.getItem('masterArray'));
                $('#dispatch_master').val(JSON.stringify(masterlocal));
                $('#headdiv :input').parent('div').addClass('no_pointer_disabled');
            }
            for (i = 0; i < local.length; i++) {
                var cnt = i + 1;
                local[i]['record'] = cnt;

//                var parent_product_code = local[i]['parent_product_code'];
//
//                if (parent_product_code > 0)
//                {
//                    var cnt_val = 'danger';
//                } else
//                {
                var cnt_val = '';
//                }
                body += '<tr id=' + cnt + ' class=' + cnt_val + '>';
                body += '<td>' + cnt + '</td>';
                body += '<td>' + local[i]['vendor_type'] + '</td>';
                body += '<td>' + local[i]['vendor_code'] + '</td>';
                body += '<td>' + local[i]['vendor_name'] + '</td>';
//                body += '<td>' + local[i]['route'] + '</td>';
                body += '<td>' + local[i]['product_name'] + '</td>';
                body += '<td>' + local[i]['dispatch_qty'] + '</td>';
                body += '<td>' + local[i]['rate'] + '</td>';
                body += '<td>' + local[i]['amount'] + '</td>';
                body += '<td>' + local[i]['discount_amount'] + '</td>';
                var icon = '<span class=\'glyphicon glyphicon-pencil\'></span>';
                var icondlt = '<span class=\'glyphicon glyphicon-trash\'></span>';
                var button = '<a href="#" title="<?php echo Yii::t('app', 'Edit'); ?>" onclick=EditData(' + cnt + ')>' + icon + '</a><a href="#" title="<?php echo Yii::t('app', 'Delete'); ?>" onclick=DeleteData(' + cnt + ')>' + icondlt + '</a>';
                body += '<td class="action-icons">' + button + '</td>';
                body += '</tr>';
            }
        }
        $('#transactions tbody').html(body);
        $('#dispatch_transaction').val(JSON.stringify(local));

    }
    function bindMaster() {
        var local = JSON.parse(localStorage.getItem('masterArray'));
        if (!jQuery.isEmptyObject(local)) {
            var local = JSON.parse(localStorage.getItem('masterArray'))[0];
            $('#tblproductdispatch-challan_date').val(local['challan_date']);
            $('#tblproductdispatch-reference_no').val(local['reference_no']);
            $('#tblproductdispatch-vehicle_no').val(local['vehicle_no']);
            $('#tblproductdispatch-union_code').val(local['union_code']);
            $('#tblproductdispatch-plant_code').on('depdrop.afterChange', function (event, id, value, jqXHR, textStatus) {
//                $('#tblproductdispatch-plant_code').val(local['plant_code']);
                $('#tblproductdispatch-plant_code').val(local['plant_code']).change();
                $('#tblproductdispatch-plant_code').prop('disabled', 'disabled');
            });

            $('#tblproductdispatch-mcc_plant_code').on('depdrop.afterChange', function (event, id, value, jqXHR, textStatus) {
//                $('#tblproductdispatch-mcc_plant_code').val(local['mcc_plant_code']);
                $('#tblproductdispatch-mcc_plant_code').val(local['mcc_plant_code']).change();
                $('#tblproductdispatch-mcc_plant_code').prop('disabled', 'disabled');
            });
            $('#tblproductdispatch-bmc_code').on('depdrop.afterChange', function (event, id, value, jqXHR, textStatus) {
//                $('#tblproductdispatch-bmc_code').val(local['bmc_code']);
                $('#tblproductdispatch-bmc_code').val(local['bmc_code']).change();
                $('#tblproductdispatch-bmc_code').prop('disabled', 'disabled');
            });
            $('#tblproductdispatch-route_code').on('depdrop.afterChange', function (event, id, value, jqXHR, textStatus) {
//                $('#tblproductdispatch-route_code').val(local['route_code']);
                $('#tblproductdispatch-route_code').val(local['route_code']).change();
                $('#tblproductdispatch-route_code').prop('disabled', 'disabled');
            });
//            $('#tblproductdispatch-plant_code').val(local['plant_code']);
//            $('#tblproductdispatch-mcc_plant_code').val(local['mcc_plant_code']);
//            $('#tblproductdispatch-bmc_code').val(local['bmc_code']);
//            $('#tblproductdispatch-route_code').val(local['route_code']);
            $('#headdiv :input').attr('disabled', true);
            $('#headdiv select').prop('disabled', 'disabled');
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
            $("#addbutton").text('<?php echo Yii::t('app', 'Update Product') ?>');
            $("#addbutton").prop('disabled', false);
            document.getElementById(cnt).bgColor = '#c8cace';
            $('#edit_tr').val(cnt);
            $('#tblproductdispatch-vendor_type').val(local['vendor_type']);
//                if (local['dcs_code'] != '') {
//                }
            $('#tblproductdispatch-dcs_code').val(local['dcs_code']);
            $('#tblproductdispatch-dcs_code').val(local['dcs_code']).change();
//                if (local['dcs_code'] != '') {
            $("#tblproductdispatch-dcs_code").prop("disabled", "disabled");
//                }
            $('#tblproductdispatchtransaction-dispatch_qty').val(local['dispatch_qty']);
            $('#tblproductdispatchtransaction-rate').val(local['rate']);
            $('#tblproductdispatchtransaction-amount').val(local['amount']);
            $('#tblproductdispatchtransaction-product_code').val(local['product_code']);
//            var option = new Option(local['product_name'], local['product_code']);
//            option.selected = true;
//            $("#tblproductmaterialdispatchtransaction-product_code").append(option);
//            $("#tblproductmaterialdispatchtransaction-product_code").prop("disabled", "disabled");
            $('#tblproductdispatchtransaction-discount_amount').val(local['discount_amount']);
//            $('#tblproductmaterialdispatchtransaction-product_scheme_code').val(local['product_scheme_code']);
//            $('#scheme_type').val(local['scheme_type']);
//            $('#tblproductmaterialdispatchtransaction-parent_product_code').val(local['parent_product_code']);
//            $('#scheme_trans_code').val(local['scheme_trans_code']);

        }
    }
    function DeleteData(cnt) {
        bootbox.confirm({
            message: "<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-3x fa-times-circle text-info\'></i></div><span><?php echo Yii::t('app', 'Are you sure to delete this product ?') ?></span></div></div>",
            callback: function (result) {
                if (result) {
                    var local = JSON.parse(localStorage.getItem('transactionsArray'))[cnt - 1];
                    if (local) {
                        var oldItems = JSON.parse(localStorage.getItem('transactionsArray')) || [];
                        oldItems.splice(cnt - 1, 1);
                        localStorage.setItem('transactionsArray', JSON.stringify(oldItems));
                        bindvalue();

                    }
                }
            }
        });
    }
    function cleardata() {
        $('#tblproductdispatchtransaction-product_code').val('');
        $('#tblproductdispatchtransaction-dispatch_qty').val('');

    }
</script>
<?php
$script = " 
    //localStorage.removeItem('transactionsArray');
    //localStorage.removeItem('masterArray');
   var local = JSON.parse(localStorage.getItem('transactionsArray'));         
   var masterlocal = JSON.parse(localStorage.getItem('masterArray'));
   $('#dispatch_master').val(JSON.stringify(masterlocal));
   $('#dispatch_transaction').val(JSON.stringify(local));
    bindvalue(); 
    bindMaster();
       
    $('#tblproductmaterialdispatchtransaction-dcs_code').on('change',function(){  
     cleardata(); 
     });

    $('#tblproductdispatchtransaction-product_code').on('change',function(){
        setProductRate();
    });
    
    $('#tblproductdispatch-bmc_code').on('change',function(){
        setProductRate();
    });
    $('#tblproductdispatch-dcs_code').on('change',function(){
        setProductRate();
    });
    
    $('#tblproductdispatch-vendor_type').on('change',function(){
        setProductRate();
    });
    function setProductRate(){
        $('#tblproductmaterialdispatchtransaction-dispatch_qty').val('');
        var product_code = $('#tblproductdispatchtransaction-product_code').val();
        var dispatchdate =$('#tblproductdispatch-challan_date').val();
        var vendorType =$('#tblproductdispatch-vendor_type').val();
        var vendorcode =$('#tblproductdispatch-bmc_code').val();
        var bmc_code =$('#tblproductdispatch-bmc_code').val();
        var union_code =$('#tblproductdispatch-union_code').val();
        if(vendorType == 'DCS') {
            vendorcode =$('#tblproductdispatch-dcs_code').val();
        }
        if(product_code!=''){
            $.ajax({
                type: 'post',
                url: '" . Url::to(['/product/tbl-product-dispatch/validate-product']) . "', 
                data: {product_code: product_code, challan_date: dispatchdate, customer_type: vendorType, customer_code: vendorcode, bmc_code: bmc_code, union_code: union_code},
                success: function(data) {
                    var obj1 = $.parseJSON(data);
                    if (obj1.status == 'success')
                    {
                        $('#tblproductdispatchtransaction-rate').val(obj1.rate);
                        $('#tblproductdispatchtransaction-rate').attr('readonly',true);
                        $('#tblproductdispatchtransaction-amount').attr('readonly',true);
                        calc();
                    }else{
                        $('#tblproductdispatchtransaction-rate').val('');
                        $('#tblproductdispatchtransaction-amount').val('');
                        $('#tblproductdispatchtransaction-rate').attr('readonly',false);
                        $('#tblproductdispatchtransaction-amount').attr('readonly',false);
                    }
                }
            });   
        } else {
            $('#tblproductdispatchtransaction-rate').val('');
            $('#tblproductdispatchtransaction-amount').val('');
            $('#tblproductdispatchtransaction-rate').attr('readonly',false);
            $('#tblproductdispatchtransaction-amount').attr('readonly',false);
        }
    };
    
    $('#tblproductdispatchtransaction-dispatch_qty').on('blur',function(){  
        calc();
        var product = $('#tblproductdispatchtransaction-product_code').val();
        var date = $('#tblproductdispatch-challan_date').val();
        var qty = $('#tblproductdispatchtransaction-dispatch_qty').val();
        var dcs = $('#tblproductdispatchtransaction-dcs_code').val();
//        var parent_product = $('#tblproductmaterialdispatchtransaction-parent_product_code').val();
//        if(parent_product==''){
//        getProductScheme(product,date,qty,dcs);
//        }
        if(qty != ''){
            $('#addbutton').prop('disabled',false);
        } else {
            $('#addbutton').prop('disabled',true);
        }
        ValidateDiscount();

    });
    
    $('#tblproductdispatchtransaction-rate').on('blur',function(){   
        calc();
    });
      function getProductScheme(product,date,qty,dcs){
             localStorage.removeItem('productScheme');
            if(product!='' && date!='' && qty!='' && dcs!=''){
                    $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/inventory/tbl-product-material-dispatch/get-product-scheme']) . "',
                        data: 'product='+product+'&date='+date+'&qty='+qty+'&dcs='+dcs,
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            var scheme=obj1.scheme;
                            if (obj1.status == 'success')
                            {
                                bootbox.alert({ 
                                message: obj1.msg, 
                            });
                               if(scheme.entry != 'add_product'){                        
                                calcDiscount(scheme.scheme_value,scheme.entry);
                               }else{
                                 localStorage.setItem('productScheme', JSON.stringify(scheme));
                               } 
                                
                                $('#tblproductmaterialdispatchtransaction-product_scheme_code').val(scheme.product_scheme_code);
                                $('#scheme_type').val(scheme.entry);
                                //$('#scheme_trans_code').val(scheme.scheme_trans_code);
                            }else{
                                $('#tblproductmaterialdispatchtransaction-product_scheme_code').val('');
                                $('#scheme_type').val('');
                                //$('#scheme_trans_code').val('');
                                $('#tblproductmaterialdispatchtransaction-discount_amount').val('');                             
                            }
                        }
            });   
            }
    };
    function calcDiscount(value,type)
{
        var amt= $('#tblproductdispatchtransaction-amount').val();
        if(type=='calc_discount_per')
        {
            var disc=(amt*value)/100;
        }
        else{
            var disc=value;
        }   
        disc=disc.toFixed(2);
        $('#tblproductmaterialdispatchtransaction-discount_amount').val(disc);
     
    
}   
 $('#dispatch-without-form').submit(function(e) {
 var local = JSON.parse(localStorage.getItem('transactionsArray'));
//if (!jQuery.isEmptyObject(local)) {
  if ($('#dispatch_transaction').val()!='null' && $('#dispatch_master').val()!='null') {
     localStorage.removeItem('transactionsArray');
     localStorage.removeItem('masterArray');
  }else{
     e.preventDefault(); 
     bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-3x fa-times-circle text-info\'></i></div><span>" . Yii::t('app', 'Please add transaction details.') . "</span></div></div>');
    }
});
    function calc(){   
        var qty = $('#tblproductdispatchtransaction-dispatch_qty').val();
        var rate = $('#tblproductdispatchtransaction-rate').val();       
        var amt = parseFloat(qty)*parseFloat(rate);       
        if(!isNaN(amt)){
        amt=amt.toFixed(2);
        $('#tblproductdispatchtransaction-amount').val(amt);                     
        }
    }
  $('#tblproductdispatchtransaction-discount_amount').on('blur',function(){   
      ValidateDiscount();
     
    });    
function ValidateDiscount(){
  var value = $('#tblproductdispatchtransaction-discount_amount').val();
        var amt = $('#tblproductdispatchtransaction-amount').val();
   if((parseInt(value) > parseInt(amt))){
            bootbox.alert('<div class=\"row\"><div class=\"col-sm-12\"><div class=\'bg-info\'><i class=\"fa fa-3x fa-times-circle aria-hidden=true\"></i></div><span>" . Yii::t('app', 'Discount amount can not be grater than amount.') . "</span></div></div>',function(){
                bootbox.hideAll();
               $('#tblproductdispatchtransaction-discount_amount').focus().select();
            });
            return false;
        }
}    


$('#addbutton').prop('disabled',true);
$('#tblproductdispatchtransaction-dispatch_qty').keypress(function(){
    $('#addbutton').prop('disabled',true);
});

$('#tblproductmaterialdispatch-vehicle_no').change(function(){
    var vehicle_no = $('#tblproductmaterialdispatch-vehicle_no').val();
    var date = $('#tblproductmaterialdispatch-date').val();
    if(vehicle_no != '' && date != ''){
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/inventory/tbl-product-material-dispatch/validate-vehicle']) . "',               
            data: 'date='+date+'&vehicle_no='+vehicle_no,
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if (obj1.status == 'error')
                {
                    bootbox.alert('<div class=\"row\"><div class=\"col-sm-12\"><div class=\'bg-info\'><i class=\"fa fa-3x fa-info text-primary aria-hidden=true\"></i></div><span>" . Yii::t('app', 'Dispatch already done for selected vehicle.') . "</span></div></div>',function(){
                        bootbox.hideAll();
                        $('#tblproductmaterialdispatch-vehicle_no').focus().select();
                    });
                }
            }
        });   
    }
});
$(document).on('click', '#addbutton', function(){
//$('#addbutton').on('click', function(){
    AddTransaction();
});
";

$this->registerJs($script, View::POS_END, 'dispatch-without-requisition');
?>