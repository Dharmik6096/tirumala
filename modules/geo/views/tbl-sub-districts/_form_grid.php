<?php

use yii\helpers\Html;
?>

<div class="">
<?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'sub_district_code'],
    ['attribute' => 'sub_district_name'],
    ['attribute' => 'local_name'],
    ['attribute' => 'distsrict_name',
        'label' => 'District Name',
        'vAlign' => 'middle',
        'value' => 'districtCode.district_name', 'filter' => Html::activeTextInput($searchModel, 'distsrict_name', ['class' => 'form-control']),],
];

$grid_option = [
    'id' => 'sub-district-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'sub_district_name,sub_district_code,tbl-sub-districts/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>