<?php

use app\components\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\usermanagement\components\GhostHtml;
?>
<div class="grid-search no-effect" >
    <?php
    $form = ActiveForm::begin([
                'id' => 'approve-milk-collection',
    ]);
    ?>

    <?php
    $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model, $key, $index) use ($is_concate) {
                echo Html::activeHiddenInput($model, 'action_perform', ['value' => $model->action_perform]);
                echo Html::activeHiddenInput($model, 'operation', ['value' => $model->operation, 'class' => 'set_operation']);
                $code = $model['collection_data_alias_code'] . '###' . $model['action_perform'];
                if (!empty($is_concate)) {
                    $code = $model['collection_data_alias_code'] . '###' . $model['process_approval_code'] . '###' . $model['action_perform'];
                }
                return ['class' => 'checkbox-collection', 'value' => $code];
            }],
            ['attribute' => 'action_perform', 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
            }, 'filter' => FALSE, 'visible' => !empty($showType) ? TRUE : FALSE],
            ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code'), 'filter' => false, 'visible' => !empty($showType) ? TRUE : FALSE],
            ['attribute' => 'ex_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
            }, 'visible' => !empty($showType) ? TRUE : FALSE],
            ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                return Yii::$app->general->getCustomer($model, $model->customer_type);
            }, 'filter' => false, 'visible' => !empty($showType) ? TRUE : FALSE],
            ['header' => Yii::t('app', 'DCS Code'), 'attribute' => 'dcs_code', 'filter' => false, 'visible' => !empty($showType) ? FALSE : TRUE],
            ['attribute' => 'dcs_code',
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false, 'visible' => !empty($showType) ? FALSE : TRUE],
            ['label' => Yii::t('app', 'Member Code'), 'attribute' => 'member_code', 'value' => function($model) {
                return !empty($model->member_code) ? substr($model->member_code, -4) : '';
            }, 'visible' => !empty($showFarmer) ? TRUE : FALSE, 'filter' => false],
            ['attribute' => 'member_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
            }, 'filter' => false, 'visible' => !empty($showFarmer) ? TRUE : FALSE],
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
            ['attribute' => 'sample_no', 'filter' => false],
            ['attribute' => 'route_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
            }, 'filter' => FALSE],
            ['attribute' => 'old_route_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->oldRouteCode, 'route_name');
            }, 'filter' => FALSE],
            ['attribute' => 'old_customer_code', 'filter' => false, 'visible' => (isset($is_dcs_editable) && $is_dcs_editable)],
            ['attribute' => 'old_milk_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->oldMilkTypeCode, 'animal_type_name');
            }, 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'old_milk_quality_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->oldMilkQualityCode, 'milk_quality_type_name');
            }, 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'old_qty', 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'old_fat', 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'old_snf', 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'old_rtpl', 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'old_amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(), 'visible' => TRUE],
            ['attribute' => 'milk_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
            }, 'filter' => FALSE],
            ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkQualityCode, 'milk_quality_type_name');
            }, 'filter' => FALSE],
            ['attribute' => 'qty', 'filter' => false],
            ['attribute' => 'fat', 'filter' => false],
            ['attribute' => 'snf', 'filter' => false],
            ['attribute' => 'scheme_rate', 'filter' => false],
            ['attribute' => 'actual_rate', 'filter' => false],
            ['attribute' => 'rtpl', 'filter' => false],
            ['attribute' => 'amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
            ['attribute' => 'old_antibiotic', 'filter' => false, 'visible' => !empty($showFarmer) ? FALSE : TRUE],
            ['attribute' => 'antibiotic', 'filter' => false, 'visible' => !empty($showFarmer) ? FALSE : TRUE],
            ['attribute' => 'created_by', 'value' => function($m) {
                $createdBy = $m->createdBy;
                if (!empty($createdBy)) {
                    return !empty($createdBy->contact_person) ? $createdBy->contact_person : $createdBy->firstname;
                }
                return null;
            }, 'filter' => FALSE],
            ['attribute' => 'error_desc', 'filter' => false],
    ];

    $grid_option = [
        'id' => $id,
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>
<div class="panel-footer" >
    <?php if (!empty($dataProvider->getModels())) { ?>
        <?= Html::button(Yii::t('app', 'Approve'), ['class' => 'btn-login btn btn-primary submit', 'id' => 'approve', 'value' => 'approve', 'name' => 'approve']); ?>
        <?= Html::button(Yii::t('app', 'Reject'), ['class' => 'btn-login btn btn-primary submit', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']); ?>
    <?php }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', $url,'','btn-login'); ?> 
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
            $("#approve-milk-collection").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'approve-milk-collection');
