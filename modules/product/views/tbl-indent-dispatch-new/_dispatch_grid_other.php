<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', 'Indent Dispatch');
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'indent-dispatch',
    ]);
    ?>
    <div class="">
        <?php
        echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']);
        echo Html::hiddenInput('vehicle', 'vehicle', ['class' => 'set_vehicle']);
        echo Html::hiddenInput('ref_no', 'ref_no', ['class' => 'set_ref_no']);
        echo Html::hiddenInput('lrno', 'lrno', ['class' => 'set_lrno']);
        echo Html::activeHiddenInput($searchModel, 'union_code', ['value' => $searchModel->union_code]);
        echo Html::activeHiddenInput($searchModel, 'plant_code', ['value' => $searchModel->plant_code]);
        echo Html::activeHiddenInput($searchModel, 'mcc_plant_code', ['value' => $searchModel->mcc_plant_code]);
        echo Html::activeHiddenInput($searchModel, 'bmc_code', ['value' => $searchModel->bmc_code]);
        echo Html::activeHiddenInput($searchModel, 'route_code', ['value' => $searchModel->route_code]);
        ?>
        <?php
        $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model, $key, $index) {
                    return ['class' => 'checkbox child-checkbox-' . $model['indent_code'], 'value' => $model['indent_code']];
                }],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'filter' => FALSE],
            ['attribute' => 'ref_code', 'label' => Yii::t('app', 'DCS Ref Code'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
                }, 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS Name'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                }, 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'code', 'label' => Yii::t('app', 'Code'), 'filter' => FALSE],
            ['attribute' => 'code', 'label' => Yii::t('app', 'Ref Code'), 'value' => function($model) {
                    $code_column = ($model->customer_type == 'BULKVEN') ? 'customer_code' : 'member_code';
                    $model->{$code_column} = $model->code;
                    $code = ($model->customer_type == 'BULKVEN') ? $model->customerCode : $model->memberCode;
                    return Yii::$app->general->getforeignkey($code, 'ref_code');
                }, 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'code', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                    $code_column = ($model->customer_type == 'BULKVEN') ? 'customer_code' : 'member_code';
                    $model->{$code_column} = $model->code;
                    $column = ($model->customer_type == 'BULKVEN') ? 'customer_name' : 'member_name';
                    $code = ($model->customer_type == 'BULKVEN') ? $model->customerCode : $model->memberCode;
                    return Yii::$app->general->getforeignkey($code, $column);
                }, 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'status_date', 'label' => Yii::t('app', 'Indent Approve Date'), 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->status_date);
                }, 'filter' => FALSE],
            ['attribute' => 'warehouse_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->warehouseCode, 'store_location_name');
                }, 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'product_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
                }, 'filter' => FALSE],
            ['attribute' => 'available_stock', 'value' => function($model) {
                    return (empty($model['warehouse_code']) || $model['warehouse_code'] == 0) ? $model->getExistingStock($model) : 0;
                }, 'filter' => FALSE],
//                ['attribute' => 'qty', 'filter' => FALSE],
            ['attribute' => 'approve_qty', 'filter' => FALSE],
            ['attribute' => 'dispatch_qty', 'filter' => FALSE,
                'format' => 'raw',
                'value' => function ($model, $key, $index) use ($form, $dispatchModel) {
                    echo Html::activeHiddenInput($dispatchModel, '[' . $key . ']indent_code', ['value' => $model->indent_code]);
                    echo Html::activeHiddenInput($dispatchModel, '[' . $key . ']approve_qty', ['value' => $model->approve_qty]);
                    return $form->field($dispatchModel, '[' . $key . ']dispatch_qty')->textInput(['value' => $dispatchModel->dispatch_qty, 'class' => 'form-control number-validate qty-dispatch qty-dispatch-' . $model->product_code . ' dispatch_qty-' . $model->indent_code, 'data-id' => $key, 'data-key' => $model->product_code])->label(FALSE);
                },
            ],
            ['attribute' => 'remaining_qty', 'label' => Yii::t('app', 'Remaining Qty'), 'filter' => FALSE,
                'format' => 'raw',
                'value' => function ($model, $key, $index) use ($form, $dispatchModel) {
                    $remaining_qty = $model->approve_qty - $dispatchModel->dispatch_qty;
                    echo Html::activeHiddenInput($dispatchModel, '[' . $key . ']remaining_qty', ['value' => $remaining_qty]);
                    echo Html::activeHiddenInput($dispatchModel, '[' . $key . ']is_close', ['value' => 1]);
                    return '<span id="tblindentdispatch-' . $key . '-remaining">' . $remaining_qty . '</span>';
                },
            ],
            ['attribute' => 'received_qty', 'filter' => FALSE],
        ];

        $grid_option = [
            'id' => 'indent-dispatch-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
            'default_sorting' => FALSE,
            'actions' => [
                'disable' => function ($url, $model) {
                    $class = ($model->is_close == 0 ) ? '' : 'disabled';
                    $options = ['data-name' => $model->dcs_code, 'data-val' => $model->indent_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Close', 'class' => 'close-indent ' . $class];                    
                    return Html::a('<i class="fa fa-times"></i>', ['/product/tbl-indent-dispatch-new/close-indent', 'id' => $model->indent_code], $options);
                },
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['indent-dispatch']);
        ?>
        <?= $form->field($dispatchModel, 'challan_date', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true, 'readonly' => 'readonly', 'value' => date('d-m-Y')])->label('Date'); ?>
        <?= $form->field($dispatchModel, 'vehicle_no', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true]) ?>            
        <?= $form->field($dispatchModel, 'reference_no', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true]) ?>            
        <?= $form->field($dispatchModel, 'lr_no', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true]) ?>            

        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Html::button(Yii::t('app', 'Dispatch'), ['class' => 'btn-login btn btn-primary submit mt10', 'id' => 'approve', 'value' => 'dispatch', 'name' => 'dispatch']);
//                echo Html::button(Yii::t('app', 'Reject'), ['class' => 'btn btn-primary submit', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index-other', false, 'btn-login mt10'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div id="AppInformation"></div>

<?php
$script = '
    $(".kv-panel-before").hide();
        
    $(document).on("blur",".qty-dispatch", function() {
        var new_tr_key = $(this).closest("tr").attr("data-key");
        remainingqty(new_tr_key);
    });

    function remainingqty(new_tr_key){
        var tr_key = $(".dispatch_qty-"+new_tr_key).attr("data-id");
        var dataKey = $(".dispatch_qty-"+new_tr_key).attr("data-key");
        var remaining_qty = $("#tblindentdispatch-" + tr_key + "-remaining_qty").val();
        var approve_qty = $("#tblindentdispatch-" + tr_key + "-approve_qty").val();
        var dispatch_qty = $("#tblindentdispatch-" + tr_key + "-dispatch_qty").val();
        dispatch_qty = dispatch_qty == "" ? 0 : dispatch_qty;
        var new_remaining_qty = parseFloat(remaining_qty)-parseFloat(dispatch_qty);
        let totalStock = parseFloat($(`#${dataKey} .total_stock`).text());
        let outOfStock = totalStock;
        $(".qty-dispatch-"+dataKey).each(function() {
            var currentValue = parseFloat($(this).val()) || 0;
            outOfStock -= currentValue;
            if(totalStock < currentValue){
                currentValue = 0;
            }
            totalStock -= currentValue;
        });
        $("#" + dataKey + " .remaining_stock").text(totalStock.toFixed(2));

        if (!isNaN(remaining_qty) && parseFloat(dispatch_qty) <= 0 && dispatch_qty != "") {
            $("#tblindentdispatch-" + tr_key + "-dispatch_qty").val("");
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Dispatch quantity must be greater than zero.</span></div></div>");
        } else if(!isNaN(remaining_qty) && parseFloat(dispatch_qty) <= parseFloat(approve_qty)){
            new_remaining_qty=parseFloat(new_remaining_qty).toFixed(2);
            $("#tblindentdispatch-" + tr_key +"-remaining").text(new_remaining_qty);                     
        } else {
            $("#tblindentdispatch-" + tr_key + "-dispatch_qty").val("");
            $("#tblindentdispatch-" + tr_key +"-remaining").text(approve_qty);
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Dispatch Qty can not be more then Remain Qty.</span></div></div>");
            return false;
        }
    }

    $(".submit").click(function() {
        var id= $(this).attr("value");
        var vehicle_no = $("#tblindentdispatch-vehicle_no").val();
        var ref_no = $("#tblindentdispatch-reference_no").val();
        var lr_no = $("#tblindentdispatch-lr_no").val();
        $(".set_operation").val(id);
        $(".set_vehicle").val(vehicle_no);
        $(".set_ref_no").val(ref_no);
        $(".set_lrno").val(lr_no);
        var len = $(".checkbox.kv-row-checkbox:checked").length;
        if(len == 0){
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Record.</span></div></div>");
            return false;
        } else if(vehicle_no ==""){
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select vehicle.</span></div></div>");
            return false;
        } else if(ref_no ==""){
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please Add Reference No.</span></div></div>");
            return false;
        } else {
            var isZeroValue = false;
            $(".qty-dispatch").each(function() {
                var id = $(this).data("id");
                var currentValue = parseFloat($(this).val()) || 0;
                if(currentValue == 0 && $(".child-checkbox-"+id).prop("checked")){
                    isZeroValue = true;
                }
            });
            if(isZeroValue){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please enter value greater than zero.</span></div></div>");
            } else {
                $(".parent-checkbox").prop("disabled", true);
                $("#indent-dispatch").submit();
            }
        }
});
';
$this->registerJs($script, View::POS_END, 'indent-dispatch');
