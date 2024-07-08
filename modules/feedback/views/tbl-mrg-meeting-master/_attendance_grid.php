<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'label' => Yii::t('app', 'MCC Name'), 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'label' => Yii::t('app', 'BMC Name'), 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'label' => Yii::t('app', 'DCS Name'), 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'label' => Yii::t('app', 'DCS Name'), 'vAlign' => 'middle', 'filter' => false],

    ['attribute' => 'member_code', 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'member_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'label' => Yii::t('app', 'Member Name'), 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'is_present', 'value' => function($model){
        return ($model->is_present == 1) ? 'Yes' : 'No';
    }, 'filter' => false],
    ['attribute' => 'reason', 'filter' => false],
];

$grid_option = [
    'id' => 'vcg-meeting-attendance-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'sign' => function ($url, $model) {
            $attachment = Yii::$app->general->getforeignkey($model->attachmentCode, 'attachment');
            return Html::a('<i class="fa fa-eye"></i>', $attachment, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Sign', 'target' => '_blank']);
        },
    ]
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
