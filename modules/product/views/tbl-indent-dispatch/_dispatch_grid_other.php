<?php

use yii\bootstrap\ActiveForm;
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
                    return ['class' => 'checkbox', 'value' => $model['dcs_code'] . '###' . $model['product_code'] . '###' . $model['qty'] . '###' . $model['warehouse_code']];
                }],
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
            ['attribute' => 'available_stock', 'value' => function($model) {
                    return !empty($model['warehouse_code']) ? $model->getExistingStock($model) : 0;
                }, 'filter' => FALSE],
            ['attribute' => 'qty', 'filter' => FALSE],
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
        <!--<div class="col-sm-2">-->
            <!--<? Yii::$app->dropdown->vehicle($dispatchModel, $form, 'vehicle_no', $dispatchModel->getAttributeLabel('vehicle_no'), false); ?>-->
        <!--</div>-->
        <?= $form->field($dispatchModel, 'vehicle_no', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true]) ?>            
        <?= $form->field($dispatchModel, 'reference_no', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true]) ?>            
        <?= $form->field($dispatchModel, 'lr_no', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true]) ?>            

        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Html::button(Yii::t('app', 'Dispatch'), ['class' => 'btn btn-primary submit', 'id' => 'approve', 'value' => 'dispatch', 'name' => 'dispatch']);
//                echo Html::button(Yii::t('app', 'Reject'), ['class' => 'btn btn-primary submit', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'indent-approval'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div id="AppInformation"></div>

<?php
$script = '
    $(".kv-panel-before").hide();
    $(".submit").click(function() {
      var id= $(this).attr("value");
      var vehicle_no = $("#tblindentdispatch-vehicle_no").val();
      var ref_no = $("#tblindentdispatch-reference_no").val();
      var lr_no = $("#tblindentdispatch-lr_no").val();
      $(".set_operation").val(id);
      $(".set_vehicle").val(vehicle_no);
      $(".set_ref_no").val(ref_no);
      $(".set_lrno").val(lr_no);
        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Record.</span></div></div>");
                return false;
            }else if(vehicle_no ==""){
                 bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select vehicle.</span></div></div>");
                return false;
            }else if(ref_no ==""){
                 bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please Add Reference No.</span></div></div>");
                return false;
            }
            else {
                $("#indent-dispatch").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'indent-dispatch');
