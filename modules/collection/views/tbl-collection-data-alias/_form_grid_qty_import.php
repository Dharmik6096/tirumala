<?php

use app\components\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<div class="grid-search no-effect" >
    <?php
    $form = ActiveForm::begin([
                'id' => 'qty_import_approval',
    ]);
    ?>

    <?php
    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model, $key, $index) {
                echo Html::activeHiddenInput($model, 'action_perform', ['value' => $model->action_perform]);
                echo Html::activeHiddenInput($model, 'operation', ['value' => $model->operation, 'class' => 'set_operation']);
                return ['class' => 'checkbox-collection', 'value' => $model['collection_data_alias_code']];
            }],
        ['header' => Yii::t('app', 'DCS Code'), 'attribute' => 'dcs_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
            },'filter' => false],
        ['attribute' => 'dcs_code',
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false],
        ['label' => Yii::t('app', 'Member Code'), 'attribute' => 'member_code', 'value' => function($model) {
                return substr($model->member_code, -4);
            }, 'filter' => false],
        ['attribute' => 'member_code', 'value' => function($model) {
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
        ['attribute' => 'milk_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
            }, 'filter' => FALSE],
         ['attribute' => 'sample_no', 'filter' => false],    
        ['attribute' => 'qty', 'filter' => false],
        ['attribute' => 'fat', 'filter' => false],
        ['attribute' => 'snf', 'filter' => false],
        ['attribute' => 'rtpl', 'filter' => false],
        ['attribute' => 'amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'error_desc', 'filter' => false],
    ];

    $grid_option = [
        'id' => 'qty_import_approval',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>
<div class="panel-footer" >
    <?php if (!empty($dataProvider->getModels())) { ?>
        <?= Html::button(Yii::t('app', 'Approve'), ['class' => 'btn btn-primary submit', 'id' => 'approve', 'value' => 'approve', 'name' => 'approve']); ?>
        <?= Html::button(Yii::t('app', 'Reject'), ['class' => 'btn btn-primary submit', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']); ?>
    <?php }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'qty-import-approval','','btn-login'); ?> 
</div>

<?php ActiveForm::end(); ?>

<?php
$script = '
    $(".kv-panel-before").hide();
    $(".submit").click(function() {
     var id= $(this).attr("value");
     $(".set_operation").val(id);
        var len = $("input[class=\"checkbox-collection kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Collection.</span></div></div>");
                return false;
            } else {
            $("#qty_import_approval").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'qty_import_approval');
