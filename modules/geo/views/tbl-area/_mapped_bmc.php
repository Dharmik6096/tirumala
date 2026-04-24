<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$applicable_type = isset($searchModel->applicable_type) ? $searchModel->applicable_type : 'BMC';

if ($applicable_type == 'DCS') {
    $attribute = [
        ['attribute' => 'applicable_code', 'filter' => false],
        ['label' => Yii::t('app', 'Code'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->tblDcs, 'ref_code');
            }, 'vAlign' => 'middle', 'filter' => false],
        ['label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->tblDcs, 'dcs_code_ex');
            }, 'vAlign' => 'middle', 'filter' => false],
        ['label' => Yii::t('app', 'Name'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->tblDcs, 'dcs_name');
            }, 'vAlign' => 'middle', 'filter' => false],
    ];
} else {
    $attribute = [
        ['attribute' => 'bmc_code', 'filter' => false],
        ['label' => Yii::t('app', 'Code'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
            }, 'vAlign' => 'middle', 'filter' => false],
        ['label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_code_ex');
            }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'bmc_name', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            }, 'vAlign' => 'middle', 'filter' => false],
    ];
}

$grid_option = [
    'id' => 'source-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => $applicable_type == 'DCS' ? 'tblDcs.dcs_name,area_bmc_mapping_code,tbl-area/delete-bmc' : 'bmcCode.bmc_name,area_bmc_mapping_code,tbl-area/delete-bmc'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>