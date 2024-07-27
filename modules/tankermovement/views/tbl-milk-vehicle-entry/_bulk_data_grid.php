<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'bulk-approval',
    ]);
    ?>
    <div class="">
        <?php
        echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']);
        echo Html::hiddenInput('remarks', 'remarks', ['class' => 'set_remarks']);
        ?>
        <?php
        $attribute = [
                [
                'class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'],
                'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model, $key, $index) {
                    return [
                        'class' => 'checkbox',
                        'value' => $model['process_approval_code'],
                    ];
                }
            ],
                ['attribute' => 'union_code', 'value' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
                }, 'filter' => false, 'visible' => false],
                ['attribute' => 'plant_code', 'value' => 'plant_code', 'filter' => false],
                ['attribute' => 'receipt_at'],
                ['attribute' => 'receipt_at_code', 'value' => function($model) {
                    echo Html::activeHiddenInput($model, '[' . $model['process_approval_code'] . ']milk_vehicle_entry_code', ['value' => $model->milk_vehicle_entry_code]);
                    $rel = Yii::$app->general->getDestRelation($model->receipt_at);
                    $att = strtolower($model->receipt_at) == 'bmc' ? 'bmc_name' : (strtolower($model->receipt_at) == 'vendor' ? 'customer_name' : (strtolower($model->receipt_at) == 'party' ? 'party_name' : 'name'));
                    if (!empty($rel))
                        return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att) . '-' . strtoupper($model->receipt_at_code);
                }, 'vAlign' => 'middle', 'filter' => false],
                ['attribute' => 'receipt_at_code',
                'label' => Yii::t('app', 'Destination Code')],
                ['attribute' => 'receipt_at_code',
                'label' => (Yii::t('app', 'Destination Ref.Code')),
                'value' => function($model) {
                    $rel = Yii::$app->general->getDestRelation($model->receipt_at);
                    $att = strtolower($model->receipt_at) == 'bmc' ? 'bmc_name' : (strtolower($model->receipt_at) == 'vendor' ? 'customer_name' : (strtolower($model->receipt_at) == 'party' ? 'party_name' : 'name'));
                    if (!empty($rel) && $att !== 'party_name')
                        return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, 'ref_code');
                }, 'vAlign' => 'middle', 'filter' => false],
                ['attribute' => 'dispatch_from'],
                ['attribute' => 'dispatch_from_code', 'value' => function($model) {
                    $rel = Yii::$app->general->getDestRelation($model->dispatch_from);
                    $att = strtolower($model->dispatch_from) == 'bmc' ? 'bmc_name' : (strtolower($model->dispatch_from) == 'vendor' ? 'customer_name' : (strtolower($model->dispatch_from) == 'party' ? 'party_name' : 'name'));
                    if (!empty($rel))
                        return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att) . '-' . strtoupper($model->dispatch_from_code);
                }, 'vAlign' => 'middle', 'filter' => false],
                ['attribute' => 'dispatch_from_code',
                'label' => Yii::t('app', 'Source Code')],
                ['attribute' => 'dispatch_from_code',
                'label' => (Yii::t('app', 'Source Ref.Code')),
                'value' => function($model) {
                    $rel = Yii::$app->general->getDestRelation($model->dispatch_from);
                    $att = strtolower($model->dispatch_from) == 'bmc' ? 'bmc_name' : (strtolower($model->dispatch_from) == 'vendor' ? 'customer_name' : (strtolower($model->dispatch_from) == 'party' ? 'party_name' : 'name'));
                    if (!empty($rel) && $att !== 'party_name')
                        return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, 'ref_code');
                }, 'vAlign' => 'middle', 'filter' => false],
                ['attribute' => 'plant_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->plantCode, 'name');
                }, 'vAlign' => 'middle', 'filter' => false],
                ['attribute' => 'transporter_code',
                'label' => Yii::t('app', 'Transporter'),
                'value' => function($model) {
                    return Yii::$app->general->getmultiforeignkey($model->vehicleCode, ['transporter'], 'transporter_name');
                }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
                ['attribute' => 'grn_no'],
                ['attribute' => 'vehicle_entry_date',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['format' => 'dd-mm-yyyy',
                        'autoclose' => true]
                ],
                'value' => function($model) {
                    return Yii::$app->controls->view_date($model->vehicle_entry_date);
                }],
                ['attribute' => 'trip_code'],
                ['attribute' => 'vehicle_code',
                'label' => Yii::t('app', 'Vehicle No.'),
                'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->vehicleCode, 'parsing_no');
                }, 'filter' => false],
                ['attribute' => 'qty'],
                [
                'attribute' => 'arrival_time',
                'value' => function($model) {
                    return Yii::$app->controls->view_time($model->arrival_time);
                }, 'filter' => false],
                ['attribute' => 'gross_weight'],
                ['attribute' => 'tare_weight'],
                [
                'attribute' => 'tare_weight_time',
                'value' => function($model) {
                    return Yii::$app->controls->view_time($model->tare_weight_time);
                }, 'filter' => false],
                ['attribute' => 'approval_status', 'value' => function($model) {
                    return isset(Yii::$app->dropdown->getRecords('provisional_status')['data'][$model->approval_status]) ? Yii::$app->dropdown->getRecords('provisional_status')['data'][$model->approval_status] : '';
                }],
                ['attribute' => 'approval_remarks', 'format' => 'raw', 'value' => function ($model, $key, $index) use ($form) {
                    return '<span class=\'approval_remarks\'>' . $form->field($model, '[' . $model['process_approval_code'] . ']approval_remarks')->textInput(['value' => $model->approval_remarks, 'class' => 'form-control',])->label(FALSE) . '</span>';
                },],
        ];

        $grid_option = [
            'id' => 'bulk-pending-approval-list-data',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
            'default_sorting' => FALSE,
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], FALSE);
        ?>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                ?>
                <?= $form->field($milkVehicleEntryModel, 'approval_remarks', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true]) ?>            
                <div class="clearfix"></div>
                <?= Html::button(Yii::t('app', 'Approve'), ['class' => 'btn btn-primary submit', 'id' => 'approve', 'value' => 'approve', 'name' => 'approve']); ?>
                <?= Html::button(Yii::t('app', 'Reject'), ['class' => 'btn btn-primary submit', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']); ?>
            <?php }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$script = '
    $(".kv-panel-before").hide();

    $(".submit").click(function() {
        var id= $(this).attr("value");
        $(".set_operation").val(id);
        var remarks = $("#tblmilkvehicleentry-approval_remarks").val();
        $(".set_remarks").val(remarks);
        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Milk Receipt.</span></div></div>");
                return false;
            } else {
                $("#bulk-approval").submit();
            }
    });
';
$this->registerJs($script, View::POS_END, 'bulk-pending-approval');
