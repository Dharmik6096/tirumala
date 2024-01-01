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
    ['attribute' => 'rate_type_code', 'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('tanker_rate_type')['data'][$model->rate_type_code]) ? Yii::$app->dropdown->getRecords('tanker_rate_type')['data'][$model->rate_type_code] : '';
        }, 'vAlign' => 'middle', 'filter' => false, 'label' => Yii::t('app', 'Rate Type')],
    ['attribute' => 'milk_quality_type_code', 'value' => 'milkQualityTypeCode.milk_quality_type_name', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'milk_type_code', 'value' => 'milkTypeCode.animal_type_name', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'std_fat', 'value' => 'std_fat', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'std_snf', 'value' => 'std_snf', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'base_rate', 'value' => 'base_rate', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'fat_ratio', 'value' => function ($model) {
            return isset($model->fat_ratio) ? $model->fat_ratio : 'N/A';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'snf_ratio', 'value' => function ($model) {
            return isset($model->snf_ratio) ? $model->snf_ratio : 'N/A';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'fat_rate', 'value' => function ($model) {
            return isset($model->fat_rate) ? $model->fat_rate : 'N/A';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'snf_rate', 'value' => function ($model) {
            return isset($model->snf_rate) ? $model->snf_rate : 'N/A';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'qty_rate', 'value' => function ($model) {
            return isset($model->qty_rate) ? $model->qty_rate : 'N/A';
        }, 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'purchase-rate-based-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => 'rate_based_code,rate_based_code,tbl-tanker-rate-details/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['create', 'id' => Yii::$app->request->get('id'), 'method' => Yii::$app->request->get('method'), 'rate_type_code' => Yii::$app->request->get('rate_type_code')]);
?>
<?php Pjax::end(); ?>