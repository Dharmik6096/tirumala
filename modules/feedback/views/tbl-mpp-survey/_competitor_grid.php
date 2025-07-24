<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
?>

<?php
$attribute = [
    ['attribute' => 'competitor_id', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->competitorCode, 'competitor_name');
        },'filter' => false],
    ['attribute' => 'producter_count', 'filter' => false],
    ['attribute' => 'milk_volume', 'filter' => false],
    ['attribute' => 'milk_rate', 'filter' => false],
    ['attribute' => 'other_input_services', 'filter' => false],
];

$grid_option = [
    'id' => 'mpp-survey-competitor-grid',
    'attributes' => $attribute,
    'active_column' => false,
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
