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
    ['attribute' => 'rate_type_code', 'value' =>function($model) {
            return isset(Yii::$app->dropdown->getRecords('tanker_rate_type')['data'][$model->rate_type_code]) ? Yii::$app->dropdown->getRecords('tanker_rate_type')['data'][$model->rate_type_code] : '';
    }, 'vAlign' => 'middle', 'filter' => false,'label'=>Yii::t('app','Rate Type')],
    ['attribute' => 'milk_quality_type_code', 'value' => 'milkQualityTypeCode.milk_quality_type_name', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'milk_type_code', 'value' => 'milkTypeCode.animal_type_name', 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'purchase-rate-based-grid',
    'attributes' => $attribute,
    'active_column' => false,
];
if (isset($delete)) {
    $grid_option['actions'] = [
        'delete' => ['option' => 'rate_type_code,rate_based_code,tbl-tanker-rate-details/delete'],
    ];
}

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['create-rate', 'id' => Yii::$app->request->get('id'), 'method' => Yii::$app->request->get('method')]);
?>
<?php Pjax::end(); ?>
