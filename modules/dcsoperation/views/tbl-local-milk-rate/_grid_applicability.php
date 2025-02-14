<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    [
        'attribute' => 'wef_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }, 'filter' => FALSE],
    ['attribute' => 'dcs_code'],
    ['attribute' => 'code_ex', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'vAlign' => 'middle',],
    ['attribute' => 'dcs_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle',],
];

$grid_option = [
    'id' => 'local-milk-rate-applicability-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['view', 'id' => Yii::$app->request->get('id')]);
?>