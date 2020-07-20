<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<div class=""></div>

<?php
$form = ActiveForm::begin([
            'id' => 'delete-milk-dispatch',
//            'action' => $action,
        ]);
?>
<div class="grid-search no-effect" >

    <?php
    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model) {
                return ['class' => 'checkbox-collection', 'value' => $model['dcs_milk_dispatch_code']];
            }],
        ['attribute' => 'dcs_code', 'filter' => FALSE],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
            }],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false],
        ['label' => 'Date', 'attribute' => 'date_time_of_dispatch',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function($model) {
                return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->dcsMilkDispatch, 'date_time_of_dispatch'));
            }, 'filter' => false],
        ['attribute' => 'shift_code', 'value' => function($model) {
                return Yii::$app->general->getmultiforeignkey($model->dcsMilkDispatch, ['shiftCode'], 'shift');
            }, 'filter' => FALSE],
//        ['attribute' => 'sample_no', 'filter' => FALSE],
        ['attribute' => 'milk_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
            }, 'filter' => FALSE],
        ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
            }, 'filter' => FALSE],
        ['attribute' => 'dispatch_qty', 'filter' => FALSE],
        ['attribute' => 'avg_fat', 'filter' => FALSE],
        ['attribute' => 'avg_snf', 'filter' => FALSE],
        ['attribute' => 'avg_clr', 'filter' => FALSE],
        ['attribute' => 'rtpl', 'filter' => FALSE],
        ['attribute' => 'total_amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
    ];

    $grid_option = [
        'id' => 'delete-milk-dispatch-list',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>
<div class="panel-footer" >
    <?php
    if (!empty($dataProvider->getModels())) {
        echo Html::button(Yii::t('app', 'Delete'), ['class' => 'btn btn-primary', 'id' => 'delete']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
</div>

<?php ActiveForm::end(); ?>

<?php
$script = '
    $(".kv-panel-before").hide();
    $("#delete").click(function() {
        var len = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").length;
            if(len == 0){
             bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>' . Yii::t('app', 'Please select at least one Collection.') . '</span></div></div>");
                return false;
            } else {
            $("#delete-milk-dispatch").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'delete-milk-dispatch');
