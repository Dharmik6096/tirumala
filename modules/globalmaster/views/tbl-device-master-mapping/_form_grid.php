<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'device_master_code', 'value' => function($model) {
            $tab = Yii::$app->general->getforeignkey($model->deviceMasterCode, 'tab_type');
            $tabtype = !empty($tab) ? Yii::$app->dropdown->getRecords('tab_type')['data'][$tab] : '';
            $add = Yii::$app->general->getforeignkey($model->deviceMasterCode, 'mac_address');
            return $tabtype . ' (' . $add . ')';
        }, 'filter' => false],
        ['attribute' => 'applicability_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->masterCode, 'master_type');
        }, 'visible' => true],
        ['attribute' => 'applicability_code', 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
            if (in_array($model->applicability_type, [1, 2, 3])) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
            } elseif (in_array($model->applicability_type, [4, 5, 6])) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
            }
        }, 'filter' => FALSE],
        ['attribute' => 'applicability_code', 'value' => function($model) {
            if (in_array($model->applicability_type, [1, 2, 3])) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            } elseif (in_array($model->applicability_type, [4, 5, 6])) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }
        }, 'visible' => true],
        ['attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
];

$grid_option = [
    'id' => 'device-master-mapping-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'delete' => ['option' => 'device_mapping_code,device_mapping_code,tbl-device-master-mapping/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
