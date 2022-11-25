<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\View;

$operator = ['=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<='];
?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Code'), 'value' => 'bmc_code', 'vAlign' => 'middle', 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'bmc_ref_code', 'label' => (Yii::t('app', 'BMC Ref.Code')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle'],
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
        }],
    ['attribute' => 'shift_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }
    ],
    ['attribute' => 'doc_no'],
    ['attribute' => 'sample_no', 'value' => 'sample_no', 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'fat', 'value' => 'fat', 'vAlign' => 'middle', 'filter' => Html::activeTextInput($searchModel, 'fat', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_fat', $operator, ['class' => 'form-control'])],
    ['attribute' => 'snf', 'value' => 'snf', 'vAlign' => 'middle', 'filter' => Html::activeTextInput($searchModel, 'snf', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_snf', $operator, ['class' => 'form-control'])],
    ['attribute' => 'water', 'visible' => false, 'value' => 'water', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'clr', 'visible' => false, 'value' => 'clr', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'qlty_auto', 'value' => function($model) {
            return isset($model->qlty_auto) ? Yii::$app->dropdown->getRecords('is_quality_auto')['data'][$model->qlty_auto] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('is_quality_auto', $searchModel, 'qlty_auto'),],
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
    'id' => 'tbl-quality-collection-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'default_sorting' => FALSE
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php

$script = "
$(document).ready(function(){
        setInterval(() => {
            console.log('test');
            $.pjax.reload({container: '#tbl-quality-collection-grid'});
        },120000);
});";
$this->registerJs($script, View::POS_END, 'tbl-quality-collection-grid');
?>