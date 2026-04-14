<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use app\modules\usermanagement\components\GhostHtml;
?>
<div class="grid-search clearfix">
    <?php //echo $this->render('_search', ['model' => $searchModel]);    ?>
</div>
<?php
$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false,],
        ['attribute' => 'tax_code', 'value' => 'tax_code',],
        ['attribute' => 'tax_name', 'value' => 'tax_name',],
        ['attribute' => 'tax_group_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->taxGroupCode, 'tax_group_name');
        }, 'visible' => true,],
        ['attribute' => 'ref_code'],
];

$grid_option = [
    'id' => 'tax-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
//        'view' => TRUE,
        'edit' => function ($url, $model) {
            $disable = ''; //($model->disableTax()) ? '' : 'disabled';
            $options = ['title' => 'Edit', 'class' => $disable];
            return GhostHtml::a('<span class="fa fa-pencil-alt"></span>', ['/dcsaccounting/tbl-tax/update', 'id' => $model->tax_code], $options);
        },
//        'update' => true,
        'delete' => ['option' => 'tax_name,tax_code,/dcsaccounting/tbl-tax/delete'],
        'tax_details' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['data-name' => $model->tax_name, 'data-val' => $model->tax_code, 'class' => $disable, 'title' => Yii::t('app', "Tax Detail")];
            return GhostHtml::a('<span><i class="fas fa-plus"></i></span>', ['/dcsaccounting/tbl-tax-detail/index', 'id' => $model->tax_code], $options);
        },
        'tax_mapping' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['data-name' => $model->tax_name, 'data-val' => $model->tax_code, 'class' => $disable, 'title' => Yii::t('app', "Tax Master Ledger Mapping")];
            return GhostHtml::a('<span><i class="fa fa-link"></i></span>', ['/dcsaccounting/tbl-tax/tax-ledger-mapping', 'id' => $model->tax_code], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
