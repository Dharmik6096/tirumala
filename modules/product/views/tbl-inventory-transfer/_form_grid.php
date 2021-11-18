<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'label' => 'Union', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true],
        ['attribute' => 'inventory_transfer_no'],
        ['label' => Yii::t('app', 'Inventory Transfer Date'), 'attribute' => 'inventory_transfer_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->inventory_transfer_date);
        }],
        ['attribute' => 'from_type', 'visible' => true],
        ['attribute' => 'from_code'],
        ['attribute' => 'to_type', 'visible' => true],
        ['attribute' => 'to_code'],
        ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'inventory-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'delete' => ['option' => 'inventory_transfer_no,inventory_transfer_code,tbl-inventory-transfer/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
