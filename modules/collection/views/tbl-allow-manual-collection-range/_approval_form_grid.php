<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;

$form = ActiveForm::begin([
            'id' => 'approve-manual-collection',
        ]);
?>
<div class="grid-search no-effect">

    <?php
    echo Html::hiddenInput('approve_remarks', 'approve_remarks', ['class' => 'set_remarks']);

    $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model, $key, $index) {
                echo Html::activeHiddenInput($model, 'operation', ['value' => $model->operation, 'class' => 'set_operation']);
                $code = $model['allow_manual_collection_code'] . '###' . $model['process_approval_code'];
                return ['class' => 'checkbox-collection', 'value' => $code];
            }],
            ['attribute' => 'bmc_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            }, 'filter' => false],
            ['label' => Yii::t('app', 'BMC Ref. Code'), 'attribute' => 'bmc_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
            }, 'visible' => true, 'filter' => false],
            ['attribute' => 'dcs_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false],
            ['label' => Yii::t('app', 'DCS Ref. Code'), 'attribute' => 'dcs_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
            }, 'visible' => true, 'filter' => false],
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
            ['attribute' => 'table_name', 'value' => function ($model) {
                return isset(Yii::$app->dropdown->getRecords('table_name')['data'][$model->table_name]) ? Yii::$app->dropdown->getRecords('table_name')['data'][$model->table_name] : '';
            }, 'filter' => false],
            ['attribute' => 'is_weight_manual', 'value' => function ($model) {
                return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_weight_manual] : '';
            }, 'filter' => false],
            ['attribute' => 'is_quality_manual', 'value' => function ($model) {
                return isset(Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_quality_manual]) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_quality_manual] : '';
            }, 'filter' => false],
            ['attribute' => 'approval_status', 'value' => function($model) {
                return isset(Yii::$app->dropdown->getRecords('manual_approve_status')['data'][$model->approval_status]) ? Yii::$app->dropdown->getRecords('manual_approve_status')['data'][$model->approval_status] : '';
            }, 'filter' => false],
            ['attribute' => 'remark', 'format' => 'raw', 'value' => function ($model, $key, $index) use ($form) {
                return '<span class=\'remark\'>' . $form->field($model, '[' . $model['allow_manual_collection_code'] . '###' . $model['process_approval_code'] . ']remark')->textInput(['value' => $model->remark, 'class' => 'form-control',])->label(FALSE) . '</span>';
            },],
    ];

    $grid_option = [
        'id' => 'approval-data',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
        'actions' => [
            'view-complain' => function ($url, $model) {
                if ($model->table_name == 'tbl_milk_collection') {
                    $url = Url::to(['tbl-allow-manual-collection-range/view-complain-info', 'id' => $model->allow_manual_collection_code]);
                    return GhostHtml::a('<i class="fa fa-eye"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'target' => '_blank',]);
                }
            },
        ]
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>
<div class="panel-footer" >
    <?php if (!empty($dataProvider->getModels())) { ?>
        <?= $form->field($manualCollectionModel, 'remark', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true]) ?>
        <div class="clearfix"></div>
        <?= Html::button(Yii::t('app', 'Approve'), ['class' => 'btn btn-primary submit', 'id' => 'approve', 'value' => 'approve', 'name' => 'approve']); ?>
        <?= Html::button(Yii::t('app', 'Reject'), ['class' => 'btn btn-primary submit', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']); ?>
    <?php }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index', '', 'btn-login'); ?> 
</div> 

<?php ActiveForm::end(); ?>

<?php
$script = '
    $(".kv-panel-before").hide();
    $(".submit").click(function() {
     var id= $(this).attr("value");
        $(".set_operation").val(id);
        var remarks = $("#tblallowmanualcollectionrange-remark").val();
        $(".set_remarks").val(remarks);
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
?>
