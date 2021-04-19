<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'p_bmc_code', 'filter' => false],
    ['label' => Yii::t('app', 'Code'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_code_ex');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'source-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => 'bmcCode.bmc_name,bmc_mapping_code,tbl-dcs-bmc/delete-bmc'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>