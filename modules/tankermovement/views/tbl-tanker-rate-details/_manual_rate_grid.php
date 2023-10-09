<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use yii\widgets\Pjax;
?>
<?php Pjax::begin(['id' => 'manual-grid']); ?> 
<?php
$attribute = [
    ['attribute' => 'rate_type_code', 'value' => function($model){ return $model->rate_typr_code=="1" ? "FAT+SNF" : "QTY";
    }, 'vAlign' => 'middle', 'filter' => false, 'label' => Yii::t('app', 'Rate Type')],
 //   ['attribute' => 'formula_code', 'value' => 'rateFormula.formula_description', 'vAlign' => 'middle', 'filter' => false],
  //  ['attribute' => 'quality_param_code', 'value' => 'qualityParamCode.param', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'milk_quality_type_code', 'value' => 'milkQualityTypeCode.milk_quality_type_name', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'milk_type_code', 'value' => 'milkTypeCode.animal_type_name', 'vAlign' => 'middle', 'filter' => false],
   ['attribute' => 'std_fat', 'value' => 'std_fat', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'std_snf', 'value' => 'std_snf', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'base_rate', 'value' => 'base_rate', 'vAlign' => 'middle', 'filter' => false],
//    ['attribute' => 'deduction_type', 'value' => function($model) {
//            return isset($model->getDeductionType()[$model->deduction_type]) ? $model->getDeductionType()[$model->deduction_type] : '';
//        }, 'vAlign' => 'middle', 'filter' => false],
//    ['attribute' => 'ref_type', 'value' => function($model) {
//            return isset($model->getRefType()[$model->ref_type]) ? $model->getRefType()[$model->ref_type] : '';
//        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'fat_rate', 'value' => function($model) {
            return isset($model->fat_rate) ? $model->fat_rate : '';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'snf_rate', 'value' =>function($model) {
            return isset($model->snf_rate) ? $model->snf_rate : '';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'qty_rate', 'value' => function($model) {
            return isset($model->qty_rate) ? $model->qty_rate : '';
        }, 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'purchase-rate-based-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

if (isset($delete)) {
    $grid_option['actions'] = [
        'delete' => ['option' => 'rate_based_code,rate_based_code,tbl-tanker-rate-details/delete'],
    ];
}
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['create', 'id' => Yii::$app->request->get('id'), 'method' => Yii::$app->request->get('method'), 'rate_type_code' => Yii::$app->request->get('rate_type_code')]);
?>
<?php Pjax::end(); ?>
<div class="col-sm-12 mt25">
    <?php
    if (Yii::$app->request->get('id') != -1 && count($dataProvider->getModels()))
        echo Html::a(Yii::t('app', 'Generate'), ['generate-chart', 'id' => $searchModel->purchase_rate_code], ['class' => 'btn btn-primary apply-shortcut', 'shortcut_key' => 'ctrl+alt+e'])
        ?>
</div>