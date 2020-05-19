<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<div class="grid-search clearfix">
    <?php //echo $this->render('_search', ['model' => $searchModel]);    ?>
</div>
<?php
$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false,],
        ['attribute' => 'basic_tax_code', 'value' => 'basic_tax_code',],
        ['attribute' => 'basic_tax_name', 'value' => 'basic_tax_name',],
];

$grid_option = [
    'id' => 'basic-tax-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
//        'view' => TRUE,
        'edit' => function ($url, $model) {
//            $disable = (trim($model->basic_tax_code) == 1) ? 'disabled' : '';
            $disable = '';
            $options = ['class' => $disable, 'title' => 'Update'];
            return GhostHtml::a('<span title="Edit"><i class="glyphicon glyphicon-pencil"></i></span>', ['/dcsaccounting/tbl-basic-tax/update', 'id' => $model->basic_tax_code], $options);
        },
        'delete' => ['option' => 'basic_tax_name,basic_tax_code,/dcsaccounting/tbl-basic-tax/delete,checkGrossAmount()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
