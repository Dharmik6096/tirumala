<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
?>
<div class="grid-search no-effect" >
    <?php
    $form = ActiveForm::begin([
        'id' => 'approve-milk-collection',
    ]);
    $showFarmer = $showFarmer ?? null;
    $showType = $showType ?? null;
    $is_dcs_editable = $is_dcs_editable ?? null;
    ?>
    <?php echo Html::hiddenInput('operation', '', ['class' => 'set_operation']); ?>

    <?php
    $attribute = [
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'],
                'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model, $key, $index) {
                    $dcsName = Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                    $dateVal = Yii::$app->controls->view_date($model->date_time_of_collection);
                    $shiftName = Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
                    return [
                        'class' => 'checkbox group-checkbox parent-checkbox parent_row_' . $index, 
                        'data-parent-id' => 'parent_row_' . $index, 
                        'data-dcs-name' => $dcsName,
                        'data-date' => $dateVal,
                        'data-shift' => $shiftName,
                        'value' => ''
                    ];
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
                'defaultHeaderState' => GridView::ROW_EXPANDED,
                'enableCache' => false,
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_EXPANDED;
                },
                'detail' => function ($model, $key, $index, $column) use ($form, $dataProvider, $searchModel, $is_concate, $showFarmer, $showType, $is_dcs_editable) {
                    return Yii::$app->controller->renderPartial('_form_grid_new', ['model' => $model, 'form' => $form, 'dataProvider' => $dataProvider, 'searchModel' => $searchModel, 'is_concate' => $is_concate, 'showFarmer' => $showFarmer, 'showType' => $showType, 'is_dcs_editable' => $is_dcs_editable, 'parent_index' => $index]);
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'detailRowCssClass' => 'child-grid expanded-row-fix',
            ],
            ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC') . ' Ref Code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
            }, 'filter' => FALSE],
            ['attribute' => 'bmc_name', 'label' => Yii::t('app', 'BMC Name'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            }, 'filter' => FALSE],
            ['attribute' => 'mpp_code_ref', 'label' => Yii::t('app', 'Ref Code'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
            }, 'filter' => FALSE],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'filter' => FALSE],
            ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS Name'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                }, 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'date_time_of_collection', 'label' => Yii::t('app', 'Date'), 'value' => function($model) {
                return Yii::$app->controls->view_date($model->date_time_of_collection);
            }, 'filter' => FALSE],
            ['attribute' => 'shift_code', 'label' => Yii::t('app', 'Shift'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
            }, 'filter' => FALSE],

            ['attribute' => 'amount_auto_sum', 'label' => Yii::t('app', 'Farmer Collection Auto Amount'), 'format' => ['decimal', 2], 'filter' => FALSE, 'contentOptions' => function ($model, $key, $index, $column) {
                return ['class' => 'parent_row_' . $index . '_auto', 'data-val' => $model['amount_auto_sum'] ?: 0];
            }],
            ['attribute' => 'amount_manual_sum', 'label' => Yii::t('app', 'Farmer Collection Manual Amount'), 'format' => ['decimal', 2], 'filter' => FALSE, 'contentOptions' => function ($model, $key, $index, $column) {
                return ['class' => 'parent_row_' . $index . '_manual'];
            }],
            ['attribute' => 'bmc_collection_amount', 'label' => Yii::t('app', 'BMC Collection Amount'), 'format' => ['decimal', 2], 'filter' => FALSE,'value' => function ($model) {
                return $model['bmc_collection_amount'] ?: 0;
            }, 'contentOptions' => function ($model, $key, $index, $column) {
                return ['class' => 'parent_row_' . $index . '_bmc', 'data-val' => $model['bmc_collection_amount'] ?: 0];
            }],
    ];

    $grid_option = [
        'id' => $id,
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
        'default_sorting' => FALSE,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>
<div class="panel-footer" >
    <?php if (!empty($dataProvider->getModels())) { ?>
        <?= Html::button(Yii::t('app', 'Approve'), ['class' => 'btn btn-primary submit btn-login', 'id' => 'approve', 'value' => 'approve', 'name' => 'approve']); ?>
        <?= Html::button(Yii::t('app', 'Reject'), ['class' => 'btn btn-primary submit btn-login', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']); ?>
    <?php }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', $url,'','btn-login'); ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$script = '
    $(".kv-panel-before").hide();
    $(".parent-checkbox").prop("disabled", false);
    
    function updateManualAmount(parentId) {
        var children = $(".child_of_" + parentId);
        var manualSum = 0;
        children.filter(":checked").each(function() {
            manualSum += parseFloat($(this).attr("data-amount")) || 0;
        });
        $("." + parentId + "_manual").text(manualSum.toFixed(2));
    }

    $(document).on("click", ".select-on-check-all", function() {
        var isChecked = this.checked;
        $(".parent-checkbox").each(function() {
            var parentId = $(this).attr("data-parent-id");
            $(".child_of_" + parentId).prop("checked", isChecked);
            updateManualAmount(parentId);
        });
    });

    $(document).on("click", ".parent-checkbox", function(e) {
        var parentId = $(this).attr("data-parent-id");
        $(".child_of_" + parentId).prop("checked", this.checked);
        updateManualAmount(parentId);
    });
    
    $(document).on("click", ".child-checkbox", function(e) {
        var parentId = $(this).attr("data-parent-id");
        var children = $(".child_of_" + parentId);
        var allChecked = children.length > 0 && children.filter(":checked").length === children.length;
        $("." + parentId).prop("checked", allChecked);
        updateManualAmount(parentId);
    });

    $(".submit").click(function() {
        var id= $(this).attr("value");
        $(".set_operation").val(id);
        var len = $(".checkbox-collection:checked").length;
        if(len == 0){
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Collection.</span></div></div>");
            return false;
        }
        
        if (id === "approve") {
            var errorList = [];
            $(".parent-checkbox").each(function() {
                var parentId = $(this).attr("data-parent-id");
                var dcsName = $(this).attr("data-dcs-name") || "N/A";
                var dateVal = $(this).attr("data-date") || "N/A";
                var shiftName = $(this).attr("data-shift") || "N/A";
                
                var children = $(".child_of_" + parentId);
                if (children.filter(":checked").length > 0) {
                    var manualSum = 0;
                    children.filter(":checked").each(function() {
                        manualSum += parseFloat($(this).attr("data-amount")) || 0;
                    });
                    var autoAmount = parseFloat($("." + parentId + "_auto").attr("data-val")) || 0;
                    var bmcAmount = parseFloat($("." + parentId + "_bmc").attr("data-val")) || 0;
                    
                    if ((autoAmount + manualSum).toFixed(2) > (bmcAmount + 0.00)) {
                        errorList.push("<li>' . Yii::t('app', 'DCS') . ': <b>" + dcsName + "</b> | ' . Yii::t('app', 'Date ') . ': <b>" + dateVal + "</b> | ' . Yii::t('app', 'Shift ') . ': <b>" + shiftName + "</b></li>");
                    }
                }
            });
            
            if (errorList.length > 0) {
                var errorMsg = "FAMER collection not greater than BMC collection of respective MPP for date and shift.<br><br><b>Issues found in:</b><ul>" + errorList.join("") + "</ul>";
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> " + errorMsg + "</span></div></div>");
                return false;
            }
        }
        
        $("#approve-milk-collection").submit();
    });
      ';
$this->registerJs($script, View::POS_END, 'approve-milk-collection-group-wise');
