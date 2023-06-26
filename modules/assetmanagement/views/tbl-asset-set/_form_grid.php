<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;

?>
<?php

$attribute = [
    ['attribute' => 'asset_set_code'],
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => FALSE, 'filter' => false],
    ['attribute' => 'sap_code'],
    ['attribute' => 'sloc_code'],
    ['attribute' => 'store_location_type', 'value' => function($model) {
            return Yii::$app->general->getmultiforeignkey($model->storeLocCode, ['storeLocType'], 'slt_name');
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilter('store_location_type', $searchModel, 'store_location_type', Yii::t('app', 'Select'))],
    ['label' => Yii::t('app', 'Store Location Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->storeLocCode, 'store_location_name');
        }],
    ['attribute' => 'status', 'value' => function($model) {
            return isset($model->status) ? Yii::$app->dropdown->getRecords('asset_status')['data'][$model->status] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('asset_status', $searchModel, 'status')],
    ['attribute' => 'reference_code', 'visible' => FALSE],
];

$grid_option = [
    'id' => 'asset-set-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => true,
        ]];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
