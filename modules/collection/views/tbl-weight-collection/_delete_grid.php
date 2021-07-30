<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Bulk Delete Collection');
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'delete-bulk',
    ]);
    ?>
    <div class="">

        <?php
        $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model) {
                    return ['class' => 'checkbox', 'value' => $model['uuid']];
                }],
            ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Code'), 'value' => 'bmc_code', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'bmc_ref_code', 'label' => (Yii::t('app', 'BMC Ref.Code')), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
                }, 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'bmc_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                }, 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'route_code', 'label' => Yii::t('app', 'Route Code'), 'filter' => false],
            ['attribute' => 'route_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
                }, 'filter' => false],
            ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
                }, 'filter' => false],
            ['attribute' => 'customer_code', 'filter' => false],
            ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
                }, 'filter' => false],
            ['attribute' => 'ref_code', 'label' => Yii::t('app', 'Ref. Code'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->customer_type, false, false, TRUE);
                }, 'filter' => false],
            ['attribute' => 'customer_name', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->customer_type);
                }, 'filter' => false],
            [
                'attribute' => 'date_time_of_collection',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['format' => 'dd-mm-yyyy',
                        'autoclose' => true]
                ],
                'value' => function($model) {
                    return Yii::$app->controls->view_date($model->date_time_of_collection);
                }, 'filter' => false],
            ['attribute' => 'shift_code', 'filter' => false, 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
                }
            ],
            ['attribute' => 'doc_no', 'filter' => false],
            ['attribute' => 'sample_no', 'value' => 'sample_no', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'milk_type', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
                }
            ],
            ['attribute' => 'milk_quality_type', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->milkQualityTypeCode, 'milk_quality_type_name');
                }, 'filter' => false
            ],
            ['attribute' => 'qty', 'value' => 'qty', 'vAlign' => 'middle', 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'qty_mode',
                'value' => function ($model) {
                    return isset($model->qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->qty_mode] : '';
                }, 'filter' => FALSE,],
            ['attribute' => 'converted_qty', 'value' => 'converted_qty', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'weight_datetime', 'value' => function($model) {
                    return Yii::$app->controls->view_datetime($model->weight_datetime);
                }, 'filter' => false],
        ];

        $grid_option = [
            'id' => 'delete-bulk-list',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
        ];
        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Html::button(Yii::t('app', 'Delete'), ['class' => 'btn btn-primary', 'id' => 'delete']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$script = '
    $(".kv-panel-before").hide();
    $("#delete").click(function() {
        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Collection Data.</span></div></div>");
                return false;
            } else {
            $("#delete-bulk").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'delete-map-route');
