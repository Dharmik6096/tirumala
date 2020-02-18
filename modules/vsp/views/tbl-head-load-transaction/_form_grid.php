<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
?>

<div class="grid-search clearfix">
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'from_km', 'value' => 'from_km', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'to_km', 'value' => 'to_km', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'from_qty', 'value' => 'from_qty', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'to_qty', 'value' => 'to_qty', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'value', 'value' => 'value', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'value', 'value' => 'km_value', 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'head-load-transaction-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['view', 'id' => Yii::$app->request->get('id')]);
?>