<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>
<div>
    <h5 class="panel-heading"><?= Yii::t('app', 'Mapping Details') ?></h5>
    <?php
    $attribute = [
        ['attribute' => 'device_master_code', 'value' => function($model) {
                $tab = Yii::$app->general->getforeignkey($model->deviceMasterCode, 'tab_type');
                $tabtype = !empty($tab) ? Yii::$app->dropdown->getRecords('tab_type')['data'][$tab] : '';
                $add = Yii::$app->general->getforeignkey($model->deviceMasterCode, 'mac_address');
                return $tabtype . ' (' . $add . ')';
            }, 'filter' => false],
        ['attribute' => 'applicability_type',
            'filter' => FALSE,
            'value' => function ($model) {
                return isset($model->applicability_type) ? Yii::$app->dropdown->getRecords('applicability_type')['data'][$model->applicability_type] : '';
            },],
        ['attribute' => 'applicability_code', 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
                if (in_array($model->applicability_type, [1])) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
                } elseif (in_array($model->applicability_type, [2])) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
                }
            }, 'filter' => FALSE],
        ['attribute' => 'applicability_code', 'value' => function($model) {
                if (in_array($model->applicability_type, [1])) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                } elseif (in_array($model->applicability_type, [2])) {
                    return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                }
            }, 'visible' => true, 'filter' => FALSE],
        ['attribute' => 'wef_date', 'value' => function($model) {
                return Yii::$app->controls->view_date($model->wef_date);
            }, 'filter' => FALSE],
    ];

    $grid_option = [
        'id' => 'sample-test-batch',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'actions' => [
            'delete' => ['option' => 'device_mapping_code,device_mapping_code,tbl-device-master-mapping/delete'],
        ]
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>