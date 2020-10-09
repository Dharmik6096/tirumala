<?php
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<div class="pt5">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php

$attribute = [
  ['attribute' => 'block_code'],
    ['attribute' => 'block_name'],
    ['attribute' => 'local_name'],
    ['attribute' => 'district_name',
        'value' => 'subDistrictCode.districtCode.district_name', 'filter' => Html::activeTextInput($searchModel, 'district_name', ['class' => 'form-control']),
    ],
    ['attribute' => 'sub_district_name',
        'value' => 'subDistrictCode.sub_district_name', 'filter' => Html::activeTextInput($searchModel, 'sub_district_name', ['class' => 'form-control']),
    ]
];

$grid_option = [
    'id' => 'block-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'block_name,block_code,tbl-blocks/delete'],
        'miscellaneous' => function ($url, $model) {
                $options = ['data-name' => $model->block_name, 'data-val' => $model->block_name, 'data-toggle' => 'tooltip' , 'data-placement' => 'top', 'data-original-title' => 'Miscellaneous Info' , 'class' => 'miscellaneous'];
                return GhostHtml::a('<i class="fa fa-thumb-tack"></i>', ['/geo/tbl-block-miscellaneous/index', 'id' => $model->block_code, 'name' => $model->block_name], $options);
            }
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>


