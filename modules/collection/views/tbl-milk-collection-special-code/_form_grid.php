<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;
use yii\web\View;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
$operator = ['=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<='];
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->dcsCode, ['routeMapping'], 'route_name') == 'N/A' ? Yii::$app->general->getforeignkey($model->routeCode, 'route_name') : Yii::$app->general->getmultiforeignkey($model->dcsCode, ['routeMapping'], 'route_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['label' => Yii::t('app', 'Soc. Code'), 'visible' => TRUE, 'attribute' => 'dcs_code', 'filter' => false],
        ['label' => Yii::t('app', 'Old Soc. Code'), 'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'filter' => false],
        ['label' => Yii::t('app', 'Ref. Code'), 'attribute' => 'ref_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => true],
        ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'Society Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => true],
        ['label' => Yii::t('app', 'Member Code'), 'attribute' => 'member_code', 'value' => function($model) {
            return substr($model->member_code, -4);
        }, 'visible' => TRUE, 'filter' => false],
        ['attribute' => 'member_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'filter' => true],
        ['label' => 'Collection Date', 'attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }],
        ['attribute' => 'shift_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'filter' => false],
        ['attribute' => 'sample_no', 'vAlign' => 'middle'],
        ['attribute' => 'milk_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
        }, 'filter' => Html::activeDropDownList($searchModel, 'milk_type_code', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
        ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkQualityCode, 'milk_quality_type_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'qty', 'filter' => Html::activeTextInput($searchModel, 'qty', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_qty', $operator, ['class' => 'form-control'])],
        ['attribute' => 'fat', 'filter' => Html::activeTextInput($searchModel, 'fat', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_fat', $operator, ['class' => 'form-control'])],
        ['attribute' => 'snf', 'filter' => Html::activeTextInput($searchModel, 'snf', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_snf', $operator, ['class' => 'form-control'])],
        ['attribute' => 'qty_mode',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('p_ltr_kg', $searchModel, 'qty_mode'),
        'value' => function ($model) {
            return isset($model->qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->qty_mode] : '';
        },],
        ['attribute' => 'converted_qty', 'value' => 'converted_qty', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'rtpl', 'filter' => true],
        ['attribute' => 'scheme_rate', 'filter' => false, 'visible' => false],
        ['attribute' => 'actual_rate', 'filter' => false, 'visible' => false],
        ['attribute' => 'amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'protein', 'filter' => true, 'visible' => false],
        ['attribute' => 'density', 'filter' => true, 'visible' => false],
        ['attribute' => 'lactose', 'filter' => true, 'visible' => false],
        ['attribute' => 'incentive', 'filter' => true, 'visible' => false],
        ['attribute' => 'deduction', 'filter' => true, 'visible' => false],
        ['attribute' => 'total_amount', 'filter' => true, 'visible' => false],
        ['attribute' => 'type_of_data_receive', 'visible' => false],
        ['attribute' => 'org_type', 'filter' => FALSE,
        'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('originating_type_flag', $model, 'originating_type');
        },],
        ['attribute' => 'originating_type', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('originating_type', $searchModel, 'originating_type'),
        'value' => function ($model) {
            return Yii::$app->general->getOriginatingType($model, 'originating_type');
        },],
        ['attribute' => 'adt_param', 'filter' => FALSE, 'visible' => false],
        ['attribute' => 'adt_value', 'filter' => FALSE, 'visible' => false],
        ['label' => 'Sample Date', 'attribute' => 'qlty_time',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->qlty_time, 'php:d-m-Y H:i:s');
        }, 'filter' => FALSE],
        ['attribute' => 'antibiotic', 'filter' => FALSE],
        ['attribute' => 'originating_org_type', 'filter' => FALSE],
];

$grid_option = [
    'id' => 'milk-collection-special-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php

$script = "
$(document).ready(function(){
        setInterval(() => {
            $.pjax.reload({container: '#milk-collection-special-slist'});
        },30000);
});";
$this->registerJs($script, View::POS_END, 'milk-collection-list');
?>
