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
        ['attribute' => 'tax_group_code', 'value' => 'tax_group_code',],
        ['attribute' => 'tax_group_name', 'value' => 'tax_group_name',],
];

$grid_option = [
    'id' => 'tax-group-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'tax_group_name,tax_group_code,/dcsaccounting/tbl-tax-group/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
