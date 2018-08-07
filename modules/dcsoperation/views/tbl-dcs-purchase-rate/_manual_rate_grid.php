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
    ['attribute' => 'rate_type','label'=>Yii::t('app','Rate Type'), 'value' => function($model) { return Yii::$app->general->getforeignkey($model->rateType, 'rate_type'); }, 'vAlign' => 'middle', 'filter' => false],
     ['attribute' => 'formula_code', 'value' => function($model) { return Yii::$app->general->getforeignkey($model->rateFormula, 'formula'); }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'quality_param_code', 'value' => function($model) { return Yii::$app->general->getforeignkey($model->qualityParamCode, 'param'); }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'milk_quality_type_code', 'value' => function($model) { return Yii::$app->general->getforeignkey($model->milkQualityTypeCode, 'milk_quality_type_name'); }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'milk_type_code', 'value' => function($model) { return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name'); }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'start_range', 'value' => 'start_range', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'end_range', 'value' => 'end_range', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'kg_rate', 'value' => 'kg_rate', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'deduction_type', 'value' => function($model) {
            return isset($model->getDeductionType()[$model->deduction_type]) ? $model->getDeductionType()[$model->deduction_type] : '';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'ref_type', 'value' => function($model) {
            return isset($model->getRefType()[$model->ref_type]) ? $model->getRefType()[$model->ref_type] : '';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'fixed_point', 'value' => 'fixed_point', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'value', 'value' => 'value', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'step', 'value' => 'step', 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'purchase-rate-based-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['view', 'id' => Yii::$app->request->get('id')]);
?>
<?php Pjax::end(); ?>
