<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
?>

<?php

$attribute = [
    [
        'attribute' => 'wef_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
    ['attribute' => 'shift_code', 'value' => function($model) { return Yii::$app->general->getforeignkey($model->shiftCode, 'shift'); }, 'vAlign' => 'middle',],
    ['attribute' => 'dcs_code', 'value' => function($model) { return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'); },'label'=> Yii::t('app','DCS'), 'vAlign' => 'middle',],
    ['attribute' => 'route_name','label'=> Yii::t('app','Route Name'), 'value' => function($model) { return Yii::$app->general->getmultiforeignkey($model->dcsCode, ['routeCode'], 'route_name'); }, 'vAlign' => 'middle',],
];

$grid_option = [
    'id' => 'purchase-rate-applicability-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['view', 'id' => Yii::$app->request->get('id')]);
?>