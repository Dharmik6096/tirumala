<?php

use yii\helpers\Html;
?>

<?php

$attribute = [
    ['attribute' => 'village_miscellaneous_code'],
    ['attribute' => 'miscellaneous_code',
        'value' => 'mescellaneousCode.miscellaneous_name', 'filter' => Html::activeTextInput($searchModel, 'miscellaneous_code', ['class' => 'form-control']),],
    ['attribute' => 'description', 'filter'=>FALSE],    
    ['attribute' => 'local_description', 'vAlign' => 'middle','visible'=>false],    
];

$grid_option = [
    'id' => 'village-miscellaneous-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'mescellaneousCode.miscellaneous_name,village_miscellaneous_code,tbl-village-miscellaneous/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

