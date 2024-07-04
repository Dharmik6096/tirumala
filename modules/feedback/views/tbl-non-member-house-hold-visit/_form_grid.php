<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use kartik\grid\GridView;
use yii\helpers\Html;
?>

<?php
$attribute = [
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'label' => Yii::t('app', 'MCC Name'), 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'label' => Yii::t('app', 'BMC Name'), 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'label' => Yii::t('app', 'DCS Name'), 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'name', 'label' => Yii::t('app', 'Member Name'), 'vAlign' => 'middle'],
    ['attribute' => 'mobile_no'],
    ['attribute' => 'address_line'],
    ['attribute' => 'pincode'],
    ['attribute' => 'surveyer_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->surveyerCode, 'name');
    }],
    ['attribute' => 'visit_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->visit_date);
        }],
    ['attribute' => 'milch_animal_cow_cnt'],
    ['attribute' => 'milch_animal_buff_cnt'],
    ['attribute' => 'milch_animal_country_cow_cnt'],
    ['attribute' => 'cow_milk_volume'],
    ['attribute' => 'buff_milk_volume'],
    ['attribute' => 'total_milk_volume'],
    ['attribute' => 'own_milk_consumption'],
    ['attribute' => 'balance_milk'],
    ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'mpp-survey-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
