<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;

?>
<?php

$attribute = [
    ['attribute' => 'spare_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->spareCode, 'asset_name');
        }, 'visible' => true],
    ['attribute' => 'serial_number', 'visible' => true],
    ['attribute' => 'qty', 'visible' => true],
    ['attribute' => 'is_active', 'visible' => true],
];

$grid_option = [
    'id' => 'asset-master-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record', 'data-val' => $model->asset_detail_bom_code, 'data-code' => $model->asset_detail_bom_code, 'title' => Yii::t('app', 'Edit')];
            return GhostHtml::a_alert('<i class="fa fa-pencil"></i>', ['/assetmanagement/tbl-asset-detail-bom/updatebom'], $options);
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
