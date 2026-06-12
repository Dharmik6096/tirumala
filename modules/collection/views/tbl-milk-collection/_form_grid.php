<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;
use yii\web\View;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
//$fat = Yii::$app->general->dropdownRange('TblMilkCollection', 'fat', 3);
//$snf = Yii::$app->general->dropdownRange('TblMilkCollection', 'snf', 3);
//$qty = Yii::$app->general->dropdownRange('TblMilkCollection', 'qty', 100);
//$amount = Yii::$app->general->dropdownRange('TblMilkCollection', 'amount', 1000);
$operator = ['=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<='];
$client_code = \Yii::$app->session->get('eiplCode');
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
        ['label' => Yii::t('app', 'Soc. Code'), 'visible' => TRUE, 'attribute' => 'dcs_code', 'filter' => true],
        ['label' => Yii::t('app', 'Old Soc. Code'), 'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'filter' => false],
        ['label' => Yii::t('app', 'Ref. Code'), 'attribute' => 'ref_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        },],
        ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'Society Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }],
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
        ['attribute' => 'shift', 'value' => 'shiftCode.shift', 'filter' => false],
        ['attribute' => 'sample_no', 'vAlign' => 'middle'],
        ['attribute' => 'milk_type_code', 'value' => 'milkTypeCode.animal_type_name', 'filter' => Html::activeDropDownList($searchModel, 'milk_type_code', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
        ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
            return isset($model->milkQualityCode) ? $model->milkQualityCode->milk_quality_type_name : '';
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
    // ['attribute' => 'fat', 'filter' => Html::activeDropDownList($searchModel, 'fat', $fat,['class'=>'form-control','prompt'=>'Select FAT'])],
    // ['attribute' => 'snf', 'filter' => Html::activeDropDownList($searchModel, 'snf', $snf,['class'=>'form-control','prompt'=>'Select SNF'])],
    //['attribute' => 'qty', 'value' => 'qty', 'filter' => Html::activeDropDownList($searchModel, 'qty', $qty,['class'=>'form-control','prompt'=>'Select Qty'])],
    ['attribute' => 'rtpl', 'filter' => true],
        ['attribute' => 'scheme_rate', 'filter' => false, 'visible' => false],
        ['attribute' => 'actual_rate', 'filter' => false, 'visible' => false],
        ['attribute' => 'amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
    //['attribute' => 'amount', 'filter' => Html::activeDropDownList($searchModel, 'amount', $amount,['class'=>'form-control','prompt'=>'Select Amount'])],
//    ['label' => 'Collection Date', 'attribute' => 'date_time_of_collection', 'value' => function($model) {
//            return date('d-m-Y', strtotime($model->date_time_of_collection));
//        }, 'filter' => true],
    ['attribute' => 'mobile_no', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'mobile_no');
        }, 'filter' => false, 'visible' => false, 'visible' => false],
        ['attribute' => 'protein', 'filter' => true, 'visible' => false],
        ['attribute' => 'density', 'filter' => true, 'visible' => false],
        ['attribute' => 'lactose', 'filter' => true, 'visible' => false],
        ['attribute' => 'incentive', 'filter' => true, 'visible' => false],
        ['attribute' => 'deduction', 'filter' => true, 'visible' => false],
        ['attribute' => 'total_amount', 'filter' => true, 'visible' => false],
//    ['attribute' => 'converted_qty_mode'],
//    ['attribute' => 'milk_analyser_type_code', 'filter' => true],
//    ['attribute' => 'ws_code', 'filter' => true],
    ['attribute' => 'type_of_data_receive', 'visible' => false],
//    ['attribute' => 'originating_org_type'],
    ['attribute' => 'org_type', 'filter' => FALSE,
        'value' => function ($model) {
            return Yii::$app->general->getStaticDropdownVal('originating_type_flag', $model, 'originating_type');
        },],
        ['attribute' => 'originating_type', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('originating_type', $searchModel, 'originating_type'),
        'value' => function ($model) {
            return Yii::$app->general->getOriginatingType($model, 'originating_type');
        },],
        ['attribute' => 'tag_1', 'value' => function($model) {
            return Yii::$app->general->getSapStatus($model->tag_1 . $model->tag_2);
        }, 'filter' => false],
        ['attribute' => 'error_desc', 'filter' => FALSE],
        ['attribute' => 'adt_param', 'filter' => FALSE, 'visible' => false],
        ['attribute' => 'adt_value', 'filter' => FALSE, 'visible' => false],
        ['attribute' => 'device_lat', 'filter' => FALSE],
        ['attribute' => 'device_long', 'filter' => FALSE],
        ['attribute' => 'mob_lat', 'filter' => FALSE],
        ['attribute' => 'mob_long', 'filter' => FALSE],
        ['attribute' => 'voucher_code', 'filter' => FALSE, 'visible' => false],
        ['label' => 'Sample Date', 'attribute' => 'qlty_time',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->qlty_time, 'php:d-m-Y H:i:s');
        }],
        ['attribute' => 'antibiotic', 'filter' => FALSE],
        ['attribute' => 'originating_org_type', 'filter' => FALSE],
];





$grid_option = [
    'id' => 'milk-collection-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php

$script = "
$(document).ready(function(){
        setInterval(() => {
            $.pjax.reload({container: '#milk-collection-list'});
        },30000);
});";
$this->registerJs($script, View::POS_END, 'milk-collection-list');
?>
