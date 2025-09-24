<?php

use app\components\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\usermanagement\components\GhostHtml;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
$action = Url::to(['bulk-delete']);
?>
<div class=""></div>

<?php
$form = ActiveForm::begin([
            'id' => 'delete-milk-collection',
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
    if (!empty($dataProvider->getModels()) && empty($msg)) {
        echo Html::button(Yii::t('app', 'Delete'), ['class' => 'btn btn-primary btn-login', 'id' => 'delete']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index','','btn-login'); ?> 
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
            $("#delete-milk-collection").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'delete-milk-collection');
