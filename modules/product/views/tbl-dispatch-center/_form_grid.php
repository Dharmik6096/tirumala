<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'union name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false],
        ['attribute' => 'dispatch_center_name'],
        ['attribute' => 'dispatch_center_type_code', 'value' => function($model) {
            return $model->getDispatchCenterType($model->dispatch_center_type_code);
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'dispatch-center-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $options = ['title' => Yii::t('app', 'Edit')];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/product/tbl-dispatch-center/update', 'id' => $model->dispatch_center_code], $options);
        },
//        'delete' => ['option' => 'dispatch_center_name,dispatch_center_code,tbl-dispatch-center/delete'],
        'applicabilty' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability'];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/product/tbl-dispatch-center/dispatch-center-applicability', 'id' => $model->dispatch_center_code], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
