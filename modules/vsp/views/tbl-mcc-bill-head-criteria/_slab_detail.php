<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$attribute = [
    ['attribute' => 'from_val', 'filter' => false],
    ['attribute' => 'to_val', 'filter' => false],
    ['attribute' => 'formula_with_val', 'filter' => false],
    ['attribute' => 'for_what', 'filter' => false],
];

$grid_option = [
    'id' => 'transcation-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($bdataProvider, $bsearchModel, $grid_option, [Yii::$app->controller->action->id, 'id' => Yii::$app->request->get('id')]);
?>
