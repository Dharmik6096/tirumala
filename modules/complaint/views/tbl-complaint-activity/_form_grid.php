<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<div class="grid-search">
    <?php // $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'contact_person'],
    ['attribute' => 'issue_type'],
    ['attribute' => 'status'],
    ['attribute' => 'remarks'],
    ['attribute' => 'date'],
];

$grid_option = [
    'id' => 'complaint-activity',
    'attributes' => $attribute,
    'active_column' => false,
//    'actions' => []
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>