<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
?>

<?php
$attribute = [
        ['attribute' => 'feedback_item_name'],
];

$grid_option = [
    'id' => 'feedback-item-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
        'delete' => ['option' => 'feedback_item_name,eipl_app_feedback_item_code,tbl-eipl-app-feedback-item/delete'],
    ]
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
