<?php

use yii\bootstrap5\ActiveForm;
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
                }, 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'bmc_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                }, 'vAlign' => 'middle', 'filter' => false],
            [
                'attribute' => 'date_time_of_collection',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['format' => 'dd-mm-yyyy',
                        'autoclose' => true]
                ],
                'value' => function($model) {
                    return Yii::$app->controls->view_date($model->date_time_of_collection);
                }, 'filter' => FALSE],
            ['attribute' => 'shift_code', 'filter' => false, 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
                }
            ],
            ['attribute' => 'doc_no', 'filter' => FALSE],
            ['attribute' => 'sample_no', 'value' => 'sample_no', 'vAlign' => 'middle', 'filter' => FALSE],
            ['attribute' => 'fat', 'filter' => false],
            ['attribute' => 'snf', 'filter' => false],
            ['attribute' => 'water', 'visible' => false, 'value' => 'water', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'clr', 'visible' => false, 'value' => 'clr', 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'qlty_auto', 'value' => function($model) {
                    return isset($model->qlty_auto) ? Yii::$app->dropdown->getRecords('is_quality_auto')['data'][$model->qlty_auto] : '';
                }, 'filter' => FALSE,],
            ['attribute' => 'protein', 'filter' => FALSE, 'visible' => false],
            ['attribute' => 'density', 'filter' => FALSE, 'visible' => false],
            ['attribute' => 'lactose', 'filter' => FALSE, 'visible' => false],
            ['attribute' => 'adt_param', 'filter' => FALSE, 'visible' => false],
            ['attribute' => 'adt_value', 'filter' => FALSE, 'visible' => false],
            ['attribute' => 'quality_datetime', 'value' => function($model) {
                    return Yii::$app->controls->view_datetime($model->quality_datetime);
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
            <?= Yii::$app->controls->custombutton('Cancel', 'index','','btn-login'); ?> 
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
