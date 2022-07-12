<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name') . '-' . $model->union_code;
        }, 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name') . '-' . Yii::$app->general->getforeignkey($model->plantCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'mcc_code', 'value' => function($model) {
            $mccs = explode(',', $model->mcc_code);
            if (count($mccs) == 1) {
                $mcc = Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
                return !empty($mcc) ? $mcc . '-' . Yii::$app->general->getforeignkey($model->mccPlantCode, 'ref_code') : '';
            } else {
                return $model->mcc_code;
            }
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            $bmcs = explode(',', $model->bmc_code);
            if (count($bmcs) == 1) {
                $bmc = Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                return !empty($bmc) ? $bmc . '-' . Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code') : '';
            } else {
                return $model->bmc_code;
            }
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            $dcss = explode(',', $model->dcs_code);
            if (count($dcss) == 1) {
                $dcs = Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                return !empty($dcs) ? $dcs . '-' . Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code') : '';
            } else {
                return $model->dcs_code;
            }
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'member_code', 'value' => function($model) {
            $member = Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
            return !empty($member) ? $member . '-' . Yii::$app->general->getforeignkey($model->memberCode, 'ref_code') : '';
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->from_date);
        }],
    ['attribute' => 'to_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->to_date);
        }],
    ['attribute' => 'report_name'],
    ['attribute' => 'file_name'],
    [
        'attribute' => 'originating_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('originating_type', $searchModel, 'originating_type'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('originating_type')['data'][$model->originating_type]) ? Yii::$app->dropdown->getRecords('originating_type')['data'][$model->originating_type] : '';
        }],
    [
        'attribute' => 'data_post_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('file_status', $searchModel, 'data_post_status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('file_status')['data'][$model->data_post_status]) ? Yii::$app->dropdown->getRecords('file_status')['data'][$model->data_post_status] : '';
        }],
    ['label' => 'Tentative Response Date',
        'value' => function($model) {
            $date = $model->created_at;
            $response = date('Y-m-d', strtotime($date . ' +1 day'));
            return Yii::$app->controls->view_date($response);
        }],
    ['attribute' => 'resp_desc'],
    [
        'attribute' => 'file_name', 'label' => 'Report Data',
        'format' => 'raw',
        'value' => function($model) {
            if (!empty($model->file_name)) {
                return Html::a('<i class="fa fa-download"><i/>', $model->file_name, ['target' => '_blank']);
            }
        }],
];

$grid_option = [
    'id' => 'report-param-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>