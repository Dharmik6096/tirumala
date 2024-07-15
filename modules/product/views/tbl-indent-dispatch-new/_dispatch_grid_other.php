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
                'expandOneOnly' => true,
            ],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'filter' => FALSE],
            ['attribute' => 'ref_code', 'label' => Yii::t('app', 'Ref Code.'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
                }, 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS Name'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
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
    $(document).on("click",".parent-checkbox",function(){
        var id = $(this).attr("id");
        $("."+id).prop("checked", false);
        if($(this).prop("checked") == true){
            $("."+id).prop("checked", true);
        }
    });
    
    $(document).on("click",".child-checkbox",function(){
        var id = $(this).attr("data-id");
        $("#"+id).prop("checked", true);
        $("."+id).each(function() {
            if($(this).prop("checked") == false){
                $("#"+id).prop("checked", false);
            }
        });
    });
    
    $(document).on("blur",".qty-dispatch", function() {
//        var new_tr_key = $(this).closest("tr").attr("data-key");
        var data_key = $(this).attr("data-id");
        var data_class = $(this).attr("data-class");
        remainingqty(data_key, data_class);
    });

    function remainingqty(tr_key, data_class){
        var remaining_qty = $("#tblindentdispatch-" + tr_key + "-remaining_qty").val();
        var approve_qty = $("#tblindentdispatch-" + tr_key + "-approve_qty").val();
        var dispatch_qty = $("#tblindentdispatch-" + tr_key + "-dispatch_qty").val();
        dispatch_qty = dispatch_qty == "" ? 0 : dispatch_qty;
        var new_remaining_qty = parseFloat(remaining_qty)-parseFloat(dispatch_qty);
        var product_total_stock = $("#"+data_class+" .total_stock").text();
        var new_product_total_stock = parseFloat(product_total_stock) - parseFloat(dispatch_qty);
        $("#"+data_class+" .total_stock").text(new_product_total_stock);
        if (!isNaN(remaining_qty) && parseInt(dispatch_qty) <= 0 && dispatch_qty != "") {
            $("#tblindentdispatch-" + tr_key + "-dispatch_qty").val("");
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Dispatch quantity must be greater than zero.</span></div></div>");
        } else if(!isNaN(remaining_qty) && parseInt(dispatch_qty) <= parseInt(approve_qty)){
            new_remaining_qty=parseInt(new_remaining_qty).toFixed(2);
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
        var parentChecked = $("input[class=\'checkbox group-checkbox parent-checkbox kv-row-checkbox\']:checked").length;
        var childChecked = $("input[class*=\'child-checkbox\']:checked").length;

        if(parentChecked === 0 && childChecked === 0) {
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Record.</span></div></div>");
            return false;
        }else if(vehicle_no ==""){
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select vehicle.</span></div></div>");
            return false;
        }else if(ref_no ==""){
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please Add Reference No.</span></div></div>");
            return false;
        } else {
            $(".parent-checkbox").prop("disabled", true);
            $("#indent-dispatch-new").submit();
        }
});
';
$this->registerJs($script, View::POS_END, 'indent-dispatch');
