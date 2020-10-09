<?php

use yii\helpers\Html;
?>

<div class="pt5">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'hamlet_code'],
    ['attribute' => 'hamlet_name'],
    ['attribute' => 'local_name'],
    ['attribute' => 'district_name',
        'vAlign' => 'middle',
        'value' => 'villageCode.subDistrictCode.districtCode.district_name',
        'filter' => Html::activeTextInput($searchModel, 'district_name', ['class' => 'form-control']),
    ],
    ['attribute' => 'sub_district_name',
        'vAlign' => 'middle',
        'value' => 'villageCode.subDistrictCode.sub_district_name',
        'filter' => Html::activeTextInput($searchModel, 'sub_district_name', ['class' => 'form-control']),
    ],
    ['attribute' => 'village_name',
        'value' => 'villageCode.village_name',
        'filter' => Html::activeTextInput($searchModel, 'village_name', ['class' => 'form-control']),
    ],
];

$grid_option = [
    'id' => 'hamlet-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'hamlet_name,hamlet_code,tbl-hamlets/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>