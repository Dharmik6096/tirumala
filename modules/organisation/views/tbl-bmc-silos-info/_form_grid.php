<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;

?>
<?php

$attribute = [
    ['attribute' => 'silo_no', 'filter' => FALSE],
    ['attribute' => 'storage_capacity', 'filter' => FALSE],
    ['attribute' => 'chilling_capacity', 'filter' => FALSE],
    ['attribute' => 'manufacturer_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->manufacturerCode, 'manufacturer_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'model', 'filter' => FALSE],
    ['attribute' => 'milk_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'owning_type', 'value' => function($model) {
            return $model->owning_type == 1 ? 'Rent' : 'Own';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'description', 'filter' => FALSE],
];
if ($isaction) {
    $grid_option = [
        'id' => 'customer-master-list',
        'attributes' => $attribute,
        'active_column' => false,
        'actions' => [
            'edit' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record', 'data-val' => $model->bmc_silos_info_code, 'data-name' => $model->bmc_silos_info_code, 'title' => Yii::t('app', 'Edit')];
                return GhostHtml::a_alert('<i class="fa fa-pencil"></i>', ['/organisation/tbl-bmc-silos-info/update-silos'], $options);
            },
        ]
    ];
} else {
    $grid_option = [
        'id' => 'customer-master-list',
        'attributes' => $attribute,
        'active_column' => false,
    ];
}

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
