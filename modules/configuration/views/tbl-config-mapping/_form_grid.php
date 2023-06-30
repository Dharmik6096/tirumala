<?php

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        },
        'filter' => false, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => FALSE,
        'filter' => false],
    ['attribute' => 'org_code'],
    ['attribute' => 'config_for', 'value' => function($model) {
            return $model->config_for == 'BMC' ? Yii::$app->general->getforeignkey($model->mainBmcCode, 'bmc_name') : Yii::$app->general->getforeignkey($model->mainMccCode, 'name');
        }, 'filter' => false],
    ['attribute' => 'org_type', 'filter' => false],
    ['attribute' => 'process_name', 'filter' => false],
];
$grid_option = [
    'id' => 'config-mapping-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'mapping' => function ($url, $model) {
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'View'];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/configuration/tbl-config-mapping/mapping-view', 'union' => $model->union_code, 'plant' => $model->plant_code, 'mcc' => $model->mcc_plant_code, 'bmc' => $model->bmc_code, 'for' => $model->config_for, 'process' => $model->process_name], $options);
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>