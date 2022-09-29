<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;
use webvimark\modules\UserManagement\components\GhostHtml;

$action = Url::to(['create']);
?>
<div class=""></div>

<?php
$form = ActiveForm::begin([
            'id' => 'bmc-collection-data',
//            'action' => $action,
        ]);
?>
<div class="grid-search no-effect" >

    <?php
    echo Html::activeHiddenInput($searchModel, 'from_plant_code', ['value' => $searchModel->from_plant_code]);
    echo Html::activeHiddenInput($searchModel, 'to_plant_code', ['value' => $searchModel->to_plant_code, 'id' => 'set_to_plant_code']);
    echo Html::activeHiddenInput($searchModel, 'to_mcc_plant_code', ['value' => $searchModel->to_mcc_plant_code, 'id' => 'set_to_mcc_plant_code']);

    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model) {
                return ['class' => 'checkbox-collection', 'value' => $model['milk_collection_code']];
            }],
        ['attribute' => 'bmc_name', 'filter' => FALSE],
        ['attribute' => 'route_name', 'filter' => FALSE],
        ['attribute' => 'ref_code', 'filter' => FALSE],
        ['attribute' => 'ex_code', 'filter' => FALSE],
        ['attribute' => 'dcs_name', 'filter' => FALSE],
        ['attribute' => 'date_of_collection', 'filter' => FALSE],
        ['attribute' => 'shift', 'filter' => FALSE],
        ['attribute' => 'sample_no', 'filter' => FALSE],
        ['attribute' => 'milk_type', 'filter' => FALSE],
        ['attribute' => 'milk_quality_type', 'filter' => FALSE],
        ['attribute' => 'qty', 'filter' => FALSE],
        ['attribute' => 'fat', 'filter' => FALSE],
        ['attribute' => 'snf', 'filter' => FALSE],
        ['attribute' => 'clr', 'filter' => FALSE],
        ['attribute' => 'rtpl', 'filter' => FALSE],
        ['attribute' => 'amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
    ];

    $grid_option = [
        'id' => 'bmc-collection-data-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
    <?php if (!empty($dataProvider->getModels()) && empty($msg)) { ?>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->union_plant($searchModel, $form, 'tblbmccollectiontransfersearch-union_code', 'to_plant_code', $searchModel->getAttributeLabel('to_plant_code'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2 ">
            <?= Yii::$app->dropdown->plant_mcc($searchModel, $form, 'tblbmccollectiontransfersearch-to_plant_code', 'to_mcc_plant_code', $searchModel->getAttributeLabel('to_mcc_plant_code'), FALSE, ''); ?>
        </div>  
    <?php } ?>
</div>
<div class="col-sm-12 margin-top-10 form-group" >
    <?php
    if (!empty($dataProvider->getModels()) && empty($msg)) {
        echo Html::button(Yii::t('app', 'Transfer'), ['class' => 'btn btn-primary', 'id' => 'delete']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
</div>

<?php ActiveForm::end(); ?>

<?php
$script = '
    

    $("")
    $(".kv-panel-before").hide();
    $("#delete").click(function(e) {
        e.preventDefault();
        var len = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").length;
            var f_plant = $("#tblbmccollectiontransfersearch-from_plant_code").val();
            var t_plant = $("#tblbmccollectiontransfersearch-to_plant_code").val();
            var t_mcc = $("#tblbmccollectiontransfersearch-to_mcc_plant_code").val();

            if(t_plant ==""){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>' . Yii::t('app', 'Please select To Plant.') . '</span></div></div>");
                return false;
            } else {
                $("#set_to_plant_code").val(t_plant);
            }
            if(t_mcc ==""){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>' . Yii::t('app', 'Please select To MCC.') . '</span></div></div>");
                return false;
            } else {
                $("#set_to_mcc_plant_code").val(t_mcc);
            }
            if(f_plant == t_plant){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>' . Yii::t('app', 'Not Allow to Transfer in same Plant.') . '</span></div></div>");
                return false;
            }
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>' . Yii::t('app', 'Please select at least one Collection.') . '</span></div></div>");
                return false;
            } else {
                $("form#bmc-collection-data").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'bmc-collection-data');
