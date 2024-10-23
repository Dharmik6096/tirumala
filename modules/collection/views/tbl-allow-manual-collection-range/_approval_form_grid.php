<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
?>
<div class="grid-search no-effect" >
    <?php
    $form = ActiveForm::begin([
                'id' => 'approve-manual-collection',
    ]);
    ?>

    <?php
    $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model, $key, $index) {
                echo Html::activeHiddenInput($model, 'operation', ['value' => $model->operation, 'class' => 'set_operation']);
                $code = $model['allow_manual_collection_code'] . '###' . $model['process_approval_code'];
                return ['class' => 'checkbox-collection', 'value' => $code];
            }],
            ['attribute' => 'union_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
            }, 'filter' => false],
            ['attribute' => 'plant_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->plantCode, 'name');
            }, 'filter' => false],
            ['attribute' => 'mcc_plant_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
            }, 'filter' => false],
            ['attribute' => 'bmc_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            }, 'filter' => false],
            ['attribute' => 'dcs_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false],
            ['attribute' => 'from_date', 'value' => function($model) {
                return Yii::$app->controls->view_date($model->from_date);
            }, 'filter' => FALSE],
            ['attribute' => 'from_shift', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->fromShift, 'shift');
            }, 'filter' => FALSE],
            ['attribute' => 'to_date', 'value' => function($model) {
                return Yii::$app->controls->view_date($model->to_date);
            }, 'filter' => FALSE],
            ['attribute' => 'to_shift', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->toShift, 'shift');
            }, 'filter' => FALSE],
            ['attribute' => 'table_name', 'filter' => false],
            ['attribute' => 'entry_type', 'filter' => false],
            ['attribute' => 'application_type', 'filter' => false],
            ['attribute' => 'is_weight_manual', 'value' => function ($model) {
                return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual] : '';
            }, 'filter' => false],
            ['attribute' => 'is_quality_manual', 'value' => function ($model) {
                return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_quality_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_quality_manual] : '';
            }, 'filter' => false],
            ['attribute' => 'approval_status', 'value' => function($model) {
                return isset(Yii::$app->dropdown->getRecords('manual_approve_status')['data'][$model->approval_status]) ? Yii::$app->dropdown->getRecords('manual_approve_status')['data'][$model->approval_status] : '';
            }, 'filter' => false],
    ];

    $grid_option = [
        'id' => 'approval-data',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>
<div class="panel-footer" >
    <?php if (!empty($dataProvider->getModels())) { ?>
        <?= Html::button(Yii::t('app', 'Approve'), ['class' => 'btn btn-primary submit', 'id' => 'approve', 'value' => 'approve', 'name' => 'approve']); ?>
        <?= Html::button(Yii::t('app', 'Reject'), ['class' => 'btn btn-primary submit', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']); ?>
    <?php }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
</div>

<?php ActiveForm::end(); ?>

<?php
$script = '
    $(".kv-panel-before").hide();
    $(".submit").click(function() {
     var id= $(this).attr("value");
     $(".set_operation").val(id);
        var len = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Collection.</span></div></div>");
                return false;
            } else {
            $("#approve-manual-collection").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'approve-manual-collection');
