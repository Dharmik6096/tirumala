<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<div class="grid-searchno-effect" >
    <?php
    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model) {
                return ['class' => 'checkbox-collection', 'value' => $model['milk_collection_summary_code']];
            }],
        ['attribute' => 'union_code', 'filter' => false, 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
            }],
        ['attribute' => 'plant_code', 'filter' => false, 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->plantCode, 'name');
            }],
        ['attribute' => 'mcc_plant_code', 'filter' => false, 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
            }],
        ['attribute' => 'bmc_code', 'filter' => false, 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            }],
        ['attribute' => 'dcs_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false],
        ['label' => Yii::t('app', 'Ref. Code'), 'attribute' => 'ref_code',
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
            }, 'filter' => false],
        ['attribute' => 'date_time_of_collection',
            'filter' => false,
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->date_time_of_collection);
            }],
        ['attribute' => 'shift_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
            }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'avg_fat', 'filter' => false],
        ['attribute' => 'avg_snf', 'filter' => false],
        ['attribute' => 'total_qty', 'filter' => false],
        ['attribute' => 'total_amount', 'filter' => false],
        ['attribute' => 'kg_fat', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'kg_snf', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'sample_count', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'avg_rate', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'auto_count', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'manual_count', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'data_post_status', 'value' => function($model) {
                return isset(Yii::$app->dropdown->getRecords('data_post_status')['data'][$model->data_post_status]) ? Yii::$app->dropdown->getRecords('data_post_status')['data'][$model->data_post_status] : Yii::$app->dropdown->getRecords('data_post_status')['data'][0];
            }, 'filter' => false],
        ['attribute' => 'pick_datetime', 'value' => function($model) {
                return Yii::$app->controls->view_datetime($model->pick_datetime);
            }],
        ['attribute' => 'response_datetime', 'value' => function($model) {
                return Yii::$app->controls->view_datetime($model->response_datetime);
            }],
        ['attribute' => 'resp_status', 'filter' => false],
        ['attribute' => 'resp_desc', 'filter' => false],
    ];

    $grid_option = [
        'id' => 'milk-collection-summary-list',
        'attributes' => $attribute,
        'active_column' => false,
        'actions' => [
            'view' => TRUE,
            'repush-view' => function ($url, $model) {
                $disable = '';
                $options = ['title' => Yii::t('app', 'View Milk Collection'), 'class' => $disable];
                return GhostHtml::a('<i class="fa fa-plus-square"></i>', ['/collection/tbl-milk-collection-summary/repush-data-view', 'id' => $model->milk_collection_summary_code], $options);
            },
        ],
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>

<div class="col-sm-12 form-group" >
    <?php
    if (!empty($dataProvider->getModels())) {
        echo Html::button(Yii::t('app', 'Repush Bulk'), ['class' => 'btn-login btn btn-primary', 'id' => 'repush-bulk']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'repush-bulk-data', '', 'btn-login'); ?> 
</div>
<?php
$script = '
    $("#repush-bulk").click(function() {
        var len = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").length;
            if(len == 0){
             bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>' . Yii::t('app', 'Please select at least one Collection.') . '</span></div></div>");
                return false;
            } else {
            $.ajax({
                type: "POST",
                url: "' . Url::to(['/collection/tbl-milk-collection-summary/repush-bulk-data']) . '",
                data: {selection: $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").map(function() { return this.value; }).get()},
                success: function(data) {
                    // Handle response data
                }
            });
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'repush-milk-collection-bulk');
