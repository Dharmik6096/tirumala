<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
?>
<div class="grid-search large-search">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>
<?php
$attribute = [
    ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'vAlign' => 'middle'],
    ['attribute' => 'sub_center_code', 'value' => 'subCenterCode.sub_center_name', 'vAlign' => 'middle'],
    ['attribute' => 'collection_point_no', 'value' => 'collection_point_no', 'vAlign' => 'middle'],
//    ['attribute' => 'milk_type_code', 'value' => 'milkQualityTypeCode.animal_type_name', 'vAlign' => 'middle'],
];

$grid_option = [
    'id' => 'collection-point-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
        'delete' => ['option' => 'collection_point_no,collection_point_no,tbl-collection-point/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>