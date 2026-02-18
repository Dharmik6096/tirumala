<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

$attribute = [
        ['attribute' => 'bank_code', 'value' => 'bank_code'],
        ['attribute' => 'bank_name', 'value' => 'bank_name'],
        ['attribute' => 'local_name'],
        ['attribute' => 'short_name', 'visible' => FALSE],
        ['attribute' => 'local_short_name', 'visible' => FALSE],
        ['attribute' => 'ac_no_length', 'value' => 'ac_no_length'],
        ['attribute' => 'old_bank_code'],
        ['attribute' => 'checked_ac_no',
        'width' => '100px',
        'value' => function($model) {
            return $model->checked_ac_no == 0 ? 'No' : 'Yes';
        },
        'filter' => Html::activeDropDownList($searchModel, 'checked_ac_no', ['' => 'Select', 1 => 'Yes', 0 => 'No'], ['class' => 'form-control'])],
        ['attribute' => 'nationalized_bank',
        'width' => '100px',
        'value' => function($model) {
            return $model->nationalized_bank == 0 ? 'No' : 'Yes';
        },
        'filter' => Html::activeDropDownList($searchModel, 'nationalized_bank', ['' => 'Select', 1 => 'Yes', 0 => 'No'], ['class' => 'form-control'])],
        ['attribute' => 'is_alpha_acno_allow',
        'width' => '100px',
        'value' => function($model) {
            return $model->is_alpha_acno_allow == 0 ? 'No' : 'Yes';
        },
        'filter' => Html::activeDropDownList($searchModel, 'is_alpha_acno_allow', ['' => 'Select', 1 => 'Yes', 0 => 'No'], ['class' => 'form-control'])],
        ['attribute' => 'ledger_code',
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->ledgerCode, 'ledger_name');
            }, 'visible' => true, 'filter' => false
        ],
];

$grid_option = [
    'id' => 'bank-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'delete' => ['option' => 'bank_name,bank_code,tbl-banks/delete'],
    /* 'mapping' => function ($url, $model) {
      $class = $model->nationalized_bank == 1 ? 'link-disable' : '';
      $options = ['data-name' => $model->bank_name, 'data-val' => $model->bank_code, 'class' => $class, 'title' => 'District Mapping'];
      return GhostHtml::a('<span class="glyphicon glyphicon-link"></span>', ['/organisation/tbl-banks/map-districts', 'id' => $model->bank_code], $options);
      }, */
    //'mapping'=> ['option' => 'bank_name,bank_code,/organisation/tbl-banks/map-districts'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
