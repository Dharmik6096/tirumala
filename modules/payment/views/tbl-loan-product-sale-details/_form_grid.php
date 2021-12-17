<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>



<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => 'dcs_code', 'filter' => false, 'label' => Yii::t('app', 'DCS Code')],
        ['attribute' => 'dcs_code', 'value' => 'dcsCode.dcs_name', 'filter' => false, 'label' => Yii::t('app', 'DCS Name')],
        ['attribute' => 'member_code', 'value' => 'member_code', 'filter' => false, 'label' => Yii::t('app', 'Member Code')],
        ['attribute' => 'member_code', 'value' => 'memberCode.member_name', 'filter' => false, 'label' => Yii::t('app', 'Member Name')],
        ['attribute' => 'sale_detail_code', 'filter' => false],
//        ['attribute' => 'product_code', 'value' => function($model) {
//            $model->product_code = string($model->product_code);
//            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
//        }],
        ['attribute' => 'sale_date_time', 'value' => 'dcsCode.dcs_name', 'filter' => false],
        ['attribute' => 'amount', 'value' => 'unionCode.union_name', 'filter' => false],
        ['attribute' => 'entry_type', 'value' => 'dcsCode.dcs_name', 'filter' => false],
        ['attribute' => 'send_status', 'value' => 'unionCode.union_name', 'filter' => false],
        ['attribute' => 'response_datetime', 'value' => 'dcsCode.dcs_name', 'filter' => false],
        ['attribute' => 'picked_datetime', 'value' => 'unionCode.union_name', 'filter' => false],
        ['attribute' => 'resp_desc', 'value' => 'dcsCode.dcs_name', 'filter' => false],
        ['attribute' => 'data_inserted_from', 'value' => 'unionCode.union_name', 'filter' => false],
        ['attribute' => 'txfarmer_id', 'value' => 'dcsCode.dcs_name', 'filter' => false],
];

$grid_option = [
    'id' => 'union-credit-limit-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>