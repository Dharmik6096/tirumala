<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', 'Indent Dispatch');
?>
<div class="no-effect">
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
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'],
                'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model, $key, $index) {
                    $member_code = !empty($model['member_code']) ? $model['member_code'] : 0;
                    $id = $model['dcs_code'] . $member_code . $model['product_code'];
                    return ['class' => 'checkbox group-checkbox parent-checkbox', 'id' => $id, 'value' => ''];
                }
            ],
            [
                'class' => 'kartik\grid\ExpandRowColumn',
                'expandIcon' => '<span class="fa fa-plus"></span>',
                'collapseIcon' => '<span class="fa fa-minus"></span>',
                'expandTitle' => 'View Details',
                'expandAllTitle' => 'View All Details',
                'collapseTitle' => 'Hide Details',
                'collapseAllTitle' => 'Hide All Details',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_EXPANDED;
                },
                'detail' => function ($model, $key, $index, $column) use ($form, $dataProvider, $searchModel, $dispatchModel) {
                    return Yii::$app->controller->renderPartial('_dispatch_grid_new', ['model' => $model, 'form' => $form, 'dataProvider' => $dataProvider, 'searchModel' => $searchModel, 'dispatchModel' => $dispatchModel]);
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'detailRowCssClass' => 'child-grid',
                // 'expandOneOnly' => true,
            ],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'filter' => FALSE],
            ['attribute' => 'ref_code', 'label' => Yii::t('app', 'Ref Code.'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
                }, 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS Name'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                }, 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'warehouse_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->warehouseCode, 'store_location_name');
                }, 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'product_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
                }, 'filter' => FALSE],
            ['attribute' => 'approve_qty', 'filter' => FALSE],
            ['attribute' => 'remaining_qty', 'label' => Yii::t('app', 'Remaining Qty'), 'filter' => FALSE,
                'format' => 'raw',
                'value' => function ($model, $key, $index) use ($form, $dispatchModel) {
                    $remaining_qty = $model->approve_qty - $dispatchModel->dispatch_qty;
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
                echo Html::button(Yii::t('app', 'Dispatch'), ['class' => 'btn btn-primary submit mt10', 'id' => 'approve', 'value' => 'dispatch', 'name' => 'dispatch']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'indent-approval', '', 'mt10'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div id="AppInformation"></div>

<?php
$script = '
    $(".kv-panel-before").hide();
    $(".parent-checkbox").prop("disabled", false);
    $(document).ready(function() {
        $(".integer-input").on("input", function() {
            $(this).val($(this).val().replace(/[^\d]/, ""));
        });
    });
    $(document).on("click", ".parent-checkbox", function() {
        var id = $(this).attr("id");
        $("." + id).prop("checked", this.checked);
    });
    $(document).on("click", ".child-checkbox", function() {
        var id = $(this).data("id");
        var allChecked = $("." + id).filter(":checked").length === $("." + id).length;
        $("#" + id).prop("checked", allChecked);
    });
    
    $(document).on("blur",".qty-dispatch", function() {
        var key = $(this).attr("data-id");
        var cls = $(this).attr("data-class");
        if($("#tblindentdispatch-" + key + "-dispatch_qty").val() != ""){
            updateRemainingQty(key, cls);
        } else {
            var qty = $("#tblindentdispatch-" + key + "-qty").text();
            $("#tblindentdispatch-" + key + "-remaining").text(qty);
        }
    });

    function updateRemainingQty(key, cls) {
        var remaining = parseFloat($("#tblindentdispatch-" + key + "-remaining_qty").val());
        var approve = parseFloat($("#tblindentdispatch-" + key + "-approve_qty").val());
        var dispatch = parseFloat($("#tblindentdispatch-" + key + "-dispatch_qty").val()) || 0;
        var newRemaining = remaining - dispatch;


        let totalStock = parseFloat($(`#${cls} .total_stock`).text());
        let outOfStock = totalStock;

        $("." + cls).each(function() {
            var currentValue = parseFloat($(this).val()) || 0;
            outOfStock -= currentValue;
             if(totalStock < currentValue){
                currentValue = 0;
            }
            totalStock -= currentValue;
        });
        $("#" + cls + " .remaining_stock").text(totalStock.toFixed(2));

        if(dispatch <= 0){
            $("#tblindentdispatch-" + key + "-dispatch_qty").val("");
            $("#tblindentdispatch-" + key + "-remaining").text(approve);
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Dispatch quantity must be greater than zero.</span></div></div>");
        } else if (dispatch > approve) {
            $("#tblindentdispatch-" + key + "-dispatch_qty").val("");
            $("#tblindentdispatch-" + key + "-remaining").text(approve);
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Dispatch quantity must be greater than Approve Qty.</span></div></div>");
        } else if(outOfStock < 0) {
            $("#tblindentdispatch-" + key + "-dispatch_qty").val("");
            $("#tblindentdispatch-" + key + "-remaining").text(approve);
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Dispatch quantity cannot exceed the available product stock.</span></div></div>");
        } else {
            $("#tblindentdispatch-" + key + "-remaining").text(newRemaining.toFixed(2));
        }
    }

    $(".submit").click(function() {
        var operation = $(this).val();
        var vehicle = $("#tblindentdispatch-vehicle_no").val();
        var refNo = $("#tblindentdispatch-reference_no").val();
        var lrNo = $("#tblindentdispatch-lr_no").val();

        $(".set_operation").val(operation);
        $(".set_vehicle").val(vehicle);
        $(".set_ref_no").val(refNo);
        $(".set_lrno").val(lrNo);

        if ($(".parent-checkbox:checked, .child-checkbox:checked").length === 0) {
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please select at least one record.</span></div></div>");
            return false;
        } else if (!vehicle || !refNo) {
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please enter Vehical No and Reference No.</span></div></div>");
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
                $("#indent-dispatch-new").submit();
            }
        }
    });
';
$this->registerJs($script, View::POS_END, 'indent-dispatch');
