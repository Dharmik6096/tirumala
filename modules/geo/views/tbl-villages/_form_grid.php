<?php
use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
?>
<div class="">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php

$attribute = [
  ['attribute' => 'village_code'],
    ['attribute' => 'village_name'],
    ['attribute' => 'local_name'],
    ['attribute' => 'district_name',
        'value' => 'subDistrictCode.districtCode.district_name', 'filter' => Html::activeTextInput($searchModel, 'district_name', ['class' => 'form-control']),
    ],
    ['attribute' => 'sub_district_name',
        'value' => 'subDistrictCode.sub_district_name', 'filter' => Html::activeTextInput($searchModel, 'sub_district_name', ['class' => 'form-control']),
    ]
];

$grid_option = [
    'id' => 'village-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'village_name,village_code,tbl-villages/delete'],
        'miscellaneous' => function ($url, $model) {
                $options = ['data-name' => $model->village_name, 'data-val' => $model->village_name, 'data-bs-toggle' => 'tooltip' , 'data-placement' => 'top', 'title' => 'Miscellaneous Info' , 'class' => 'miscellaneous'];
                return GhostHtml::a('<i class="fa fa-thumb-tack"></i>', ['/geo/tbl-village-miscellaneous/index', 'id' => $model->village_code, 'name' => $model->village_name], $options);
            }
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
