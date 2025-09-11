<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
?>

<?php
$form = ActiveForm::begin([
            'id' => 'repush-milk-collection',
        ]);
?>
<div class="grid-search no-effect" >

    <?php
    $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model) {
                return ['class' => 'checkbox-collection', 'value' => $model['milk_collection_code']];
            }],
            ['attribute' => 'dcs_code',
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false],
            ['header' => 'Member Code', 'attribute' => 'member_code', 'filter' => false],
            ['header' => Yii::t('app', 'Member'), 'attribute' => 'member_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
            }, 'filter' => false],
            ['label' => 'Date', 'attribute' => 'date_time_of_collection',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->date_time_of_collection);
            }, 'filter' => false],
            ['attribute' => 'shift_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
            }, 'filter' => FALSE],
            ['attribute' => 'sample_no', 'filter' => FALSE],
            ['attribute' => 'milk_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
            }, 'filter' => FALSE],
            ['attribute' => 'milk_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
            }, 'filter' => FALSE],
            ['attribute' => 'qty', 'filter' => FALSE],
            ['attribute' => 'fat', 'filter' => FALSE],
            ['attribute' => 'snf', 'filter' => FALSE],
            ['attribute' => 'clr', 'filter' => FALSE],
            ['attribute' => 'rtpl', 'filter' => FALSE],
            ['attribute' => 'amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
            ['attribute' => 'data_post_status', 'value' => function($model) {
                return isset(Yii::$app->dropdown->getRecords('data_post_status')['data'][$model->data_post_status]) ? Yii::$app->dropdown->getRecords('data_post_status')['data'][$model->data_post_status] : Yii::$app->dropdown->getRecords('data_post_status')['data'][0];
            }, 'filter' => false],
            ['attribute' => 'picked_datetime', 'value' => function($model) {
                return Yii::$app->controls->view_datetime($model->picked_datetime);
            }, 'filter' => FALSE],
            ['attribute' => 'response_datetime', 'value' => function($model) {
                return Yii::$app->controls->view_datetime($model->response_datetime);
            }, 'filter' => FALSE],
            ['attribute' => 'resp_status', 'filter' => FALSE],
            ['attribute' => 'resp_desc', 'filter' => FALSE],
    ];

    $grid_option = [
        'id' => 'delete-bmc-collection-list',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>
<div class="col-sm-12 margin-top-10 form-group" >
    <?php
    if (!empty($dataProvider->getModels())) {
        echo Html::button(Yii::t('app', 'Repush'), ['class' => 'btn-login btn btn-primary', 'id' => 'repush']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'repush-bulk-data', '', 'btn-login'); ?> 
</div>

<?php ActiveForm::end(); ?>

<?php
$script = '
    $(".kv-panel-before").hide();
    $("#repush").click(function() {
        var len = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").length;
            if(len == 0){
             bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>' . Yii::t('app', 'Please select at least one Collection.') . '</span></div></div>");
                return false;
            } else {
            $("#repush-milk-collection").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'repush-milk-collection');
