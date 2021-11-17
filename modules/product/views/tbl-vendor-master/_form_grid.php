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
        ['attribute' => 'union_code','label'=>'Union', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true],
        ['attribute' => 'vendor_name'],
        ['attribute' => 'vendor_code'],
        ['attribute' => 'aadhaar_no', 'visible' => true],
        ['attribute' => 'pan_no'],
];

$grid_option = [
    'id' => 'product-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
        'contact-details' => function ($url, $model) {
            $class = '';
            $options = ['data-name' => $model->vendor_master_code, 'data-val' => $model->vendor_master_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Contact Details', 'class' => '' . $class];
            return GhostHtml::a('<i class="fa fa-user-circle-o"></i>', ['/product/tbl-vendor-master/contact-details', 'id' => $model->vendor_master_code], $options);
        },
        'delete' => ['option' => 'vendor_name,vendor_master_code,tbl-vendor-master/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
