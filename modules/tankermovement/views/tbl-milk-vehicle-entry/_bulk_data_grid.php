<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
?>
<div class=" no-effect">
    <?php
    $renderedCodes = [];
    $renderedCodesForRemark = [];
    $count = 0;
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
                'content' => function ($model, $key, $index) use (&$renderedCodes) {
                    if (in_array($model->process_approval_code, $renderedCodes)) {
                        return '';
                    }
                    $renderedCodes[] = $model->process_approval_code;
                    return Html::checkbox('selection[]', false, [
                        'class' => 'checkbox kv-row-checkbox',
                        'value' => $model->process_approval_code,
                    ]);
                },
                'headerOptions' => ['class' => 'skip-export'],
                'contentOptions' => ['class' => 'skip-export'],
            ],
            ['attribute' => 'source_org_type'],
            ['attribute' => 'source_org_code', 'value' => function ($model) {
                $rel = Yii::$app->general->getDestRelation($model->source_org_type);
                $att = strtolower($model->source_org_type) == 'bmc' ? 'bmc_name' : (strtolower($model->source_org_type) == 'vendor' ? 'customer_name' : (strtolower($model->source_org_type) == 'party' ? 'party_name' : 'name'));
                if (!empty($rel))
                    return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att) . '-' . strtoupper($model->source_org_code);
            }, 'label' => (Yii::t('app', 'Source Name')), 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'source_org_code', 'value' => function ($model) {
                $rel = Yii::$app->general->getDestRelation($model->source_org_type);
                $att = 'sap_vendor_code';
                if (!empty($rel))
                    return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att);
            }, 'label' => (Yii::t('app', 'Source SAP Vendor Code')), 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'destination_type'],

            ['attribute' => 'destination_code', 'value' => function ($model) {
                $rel = Yii::$app->general->getDestRelation($model->destination_type);
                $att = strtolower($model->destination_type) == 'bmc' ? 'bmc_name' : (strtolower($model->destination_type) == 'vendor' ? 'customer_name' : (strtolower($model->destination_type) == 'party' ? 'party_name' : 'name'));
                if (!empty($rel))
                    return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att) . '-' . strtoupper($model->destination_code);
            }, 'label' => (Yii::t('app', 'Dest. Name')), 'vAlign' => 'middle', 'filter' => false],

            ['attribute' => 'destination_code', 'value' => function ($model) {
                $rel = Yii::$app->general->getDestRelation($model->destination_type);
                $att = 'sap_vendor_code';
                if (!empty($rel))
                    return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att);
            }, 'label' => (Yii::t('app', 'Dest SAP Vendor Code')), 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'grn_no'],
            [
                'attribute' => 'receipt_datetime',
                'label' => (Yii::t('app', 'Receipt Date')),
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true
                    ]
                ],
                'value' => function ($model) {
                    return Yii::$app->controls->view_date($model->milkVehicleEntryCode->receipt_datetime);
                }
            ],
            ['attribute' => 'milkVehicleEntryCode.trip_code'],
            [
                'attribute' => 'vehicle_code',
                'label' => Yii::t('app', 'Vehicle No.'),
                'value' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->milkVehicleEntryCode->vehicleCode, 'parsing_no');
                },
                'filter' => false
            ],
            [
                'attribute' => 'arrival_time',
                'value' => function ($model) {
                    return Yii::$app->controls->view_time($model->milkVehicleEntryCode->arrival_time);
                },
                'filter' => false
            ],
            [
                'attribute' => 'gross_weight',
                'filter' => false
            ],
            [
                'attribute' => 'tare_weight',
                'filter' => false
            ],
            [
                'attribute' => 'chamber_quantity',
                'filter' => false
            ],
            [
                'attribute' => 'gross_weight_time',
                'value' => function ($model) {
                    return Yii::$app->controls->view_datetime($model->gross_weight_time);
                },
                'filter' => false
            ],
            [
                'attribute' => 'tare_weight_time',
                'value' => function ($model) {
                    return Yii::$app->controls->view_datetime($model->tare_weight_time);
                },
                'filter' => false
            ],
            ['attribute' => 'approval_remarks', 'format' => 'raw', 'value' => function ($model, $key, $index) use ($form, &$renderedCodesForRemark, $milkVehicleEntryModel) {
                if (in_array($model->process_approval_code, $renderedCodesForRemark)) {
                    return '';
                }
                $renderedCodesForRemark[] = $model->process_approval_code;
                return '<span class=\'approval_remarks\'>' . $form->field($milkVehicleEntryModel, '[' . $model['process_approval_code'] . ']approval_remarks')->textInput(['value' => $milkVehicleEntryModel->approval_remarks, 'class' => 'form-control',])->label(FALSE) . '</span>';
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
