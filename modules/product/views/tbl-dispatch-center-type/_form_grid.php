<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'dispatch_center_type'],
];

$grid_option = [
    'id' => 'product-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $disable = Yii::$app->general->allowUpdateDelete($model) ? '' : 'disabled';
            $options = ['title' => Yii::t('app', 'Edit'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/product/tbl-dispatch-center-type/update', 'id' => $model->dispatch_center_type_code], $options);
        },
//        'update' => true,
        // 'delete' => ['option' => 'product_name,product_code,tbl-dispatch-center-type/delete,checkAllowDelete()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
