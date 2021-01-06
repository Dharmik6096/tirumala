<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$attribute = [
    ['attribute' => 'bank_code', 'value' => 'bankCode.bank_name', 'filter' => false],
    ['attribute' => 'branch_code', 'value' => 'branchCode.branch_name', 'filter' => false],
    ['attribute' => 'bank_account_no', 'filter' => false],
    ['attribute' => 'ifsc', 'filter' => false],
    ['attribute' => 'beneficiary_name', 'filter' => false],
    ['attribute' => 'adhar_no', 'filter' => false],
    ['attribute' => 'is_default', 'value' => function($model) {
            return $model->is_default == 1 ? 'Yes' : 'No';
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'bank-list',
    'attributes' => $attribute,
    'active_column' => true,
        // 'actions' => []
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, [Yii::$app->controller->action->id, 'id' => Yii::$app->request->get('id')]);
?>
