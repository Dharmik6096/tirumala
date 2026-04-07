<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

$this->title = 'Process for Payment Disburse';
?>
<div class="grid-search no-effect">
    <?php
    $renderedCodes = [];
    $form = ActiveForm::begin([
        'id' => 'transpoter-payment-form',
        'method' => 'post'
    ]);
    ?>
    <div class="">
        <div class="grid-button-wrap">
            <?= Html::activeHiddenInput($model, 'transporter_type', ['value' => $searchModel->transporter_type]); ?>
            <?= Html::activeHiddenInput($model, 'from_date', ['value' => $searchModel->from_date]); ?>
            <?= Html::activeHiddenInput($model, 'to_date', ['value' => $searchModel->to_date]); ?>
        </div>
        <?php
        $transporterCode = array_count_values(array_column($dataProvider->getModels(), 'transporter_code'));
        $attribute = [
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_DEFAULT,
                'content' => function ($model, $key, $index) use (&$renderedCodes, $transporterCode) {
                    if (in_array($model->transporter_code, $renderedCodes)) {
                        return '';
                    }
                    $renderedCodes[] = $model->transporter_code;
                    return Html::checkbox('selection[]', false, [
                        'class' => 'checkbox kv-row-checkbox',
                        'value' => $model->transporter_code,
                    ]);
                },
                'headerOptions' => ['class' => 'skip-export'],
                'contentOptions' => function ($model) use (&$renderedCodes, $transporterCode) {
                    $code = $model->transporter_code;
                    if (in_array($model->transporter_code, $renderedCodes)) {
                        return [
                            'class' => 'default_hide_input',
                        ];
                    }
                    return [
                        'class' => 'skip-export',
                        'rowspan' => $transporterCode[$code] ?? 1,
                    ];
                },
            ],
            [
                'attribute' => 'bmc_code',
                'value' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                }
            ],
            ['attribute' => 'route_code', 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->routeCode, 'ref_code');
            }, 'label' => Yii::t('app', 'Route Code')],
            [
                'attribute' => 'route_name',
                'value' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
                }
            ],
            ['attribute' => 'transporter_name'],
            ['attribute' => 'parsing_no'],
            ['attribute' => 'bill_no'],
            [
                'label' => 'Period',
                'value' => function ($model) {
                    return Yii::$app->controls->view_date($model->from_date) . ' To ' . Yii::$app->controls->view_date($model->to_date);
                }
            ],
            ['attribute' => 'billing_method', 'visible' => FALSE],
            ['attribute' => 'no_of_days'],
            ['attribute' => 'total_kms'],
            ['attribute' => 'avg_rate'],
            ['attribute' => 'total_qty'],
            ['attribute' => 'rec_kg_fat'],
            ['attribute' => 'rec_kg_snf'],
            ['attribute' => 'qty_amount'],
            ['attribute' => 'fixed_rent'],
            ['attribute' => 'vehicle_average'],
            ['attribute' => 'fuel_consumption'],
            ['attribute' => 'fuel_rate'],
            ['attribute' => 'total_amount'],
            ['attribute' => 'fixed_amount'],
            ['attribute' => 'total_addition'],
            ['attribute' => 'total_deduction'],
            ['attribute' => 'net_amount'],
            ['attribute' => 'adjust_amount'],
            ['attribute' => 'final_amount'],
            ['attribute' => 'adjust_remark'],
            [
                'attribute' => 'payment_date',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true
                    ]
                ],
                'value' => function ($model) {
                    return Yii::$app->controls->view_date($model->payment_date);
                }
            ],
            ['attribute' => 'status'],
        ];

        $grid_option = [
            'id' => 'tp-payment-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => true,
        ];
        $rowOptions = function ($model) {
            return ['class' => 'tr-' . $model->transporter_code];
        };
        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['create'], false, $removeExportType = [], $exportEvents = [], $fixed_header = true, $rowOptions);
        ?>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) { ?>
                <?= Html::button(Yii::t('app', 'Process Payment'), ['class' => 'btn btn-primary sub', 'name' => 'tp']); ?>
            <?php
            } ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$script = '
    $(".kv-panel-before").hide();
    $(".checkbox").on("click", function(){
        var code = $(this).val();
        $(".tr-"+code).css("background-color", "");
        if($(this).is(":checked")){
            $(".tr-"+code).css("background-color", "#dff0d8");
        }
    });
    $(".sub").on("click",function(){
        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
        if(len == 0){
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Record.</span></div></div>");
            return false;
        } else {
            $("form#transpoter-payment-form").submit();
        }
    });
    ';
$this->registerJs($script, View::POS_END, 'transportar-payment-script');
