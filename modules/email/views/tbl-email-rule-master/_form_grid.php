<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
?>
<?php
$attribute = [
    ['attribute' => 'email_rule_master_id', 'value' => 'email_rule_master_id', 'vAlign' => 'middle'],
    ['attribute' => 'rule_id', 'value' => 'emailProcess.process_name', 'vAlign' => 'middle'],
//    ['attribute' => 'frequency', 'value' => 'frequency', 'vAlign' => 'middle'],
    ['attribute' => 'frequency',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('frequency_data', $searchModel),
        'value' => function($model) {
        return (Yii::$app->dropdown->getRecords('frequency_data')['data'][$model->frequency] != '') ? Yii::$app->dropdown->getRecords('frequency_data')['data'][$model->frequency] : '';}, 'vAlign' => 'middle'],
    ['attribute' => 'interval', 'value' => 'interval', 'vAlign' => 'middle'],
    ['attribute' => 'email', 'value' => 'email', 'vAlign' => 'middle'],
    ['attribute' => 'no_of_email', 'value' => 'no_of_email', 'vAlign' => 'middle'],
    ['attribute' => 'mobile', 'value' => 'mobile', 'vAlign' => 'middle'],
    ['attribute' => 'message', 'value' => 'message', 'vAlign' => 'middle', 'visible' => false],
    ['attribute' => 'email_subject', 'value' => 'email_subject', 'vAlign' => 'middle', 'visible' => false],
    ['attribute' => 'email_body', 'value' => 'email_body', 'vAlign' => 'middle', 'visible' => false],
];

$grid_option = [
    'id' => 'email-rule-master',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => true,
//        'delete' => ['option' => 'collection_point_no,collection_point_no,tbl-collection-point/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>