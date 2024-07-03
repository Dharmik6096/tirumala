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
    ['attribute' => 'mpp_survey_code'],
    ['attribute' => 'survey_person_code', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->surveyPersonCode, 'name');
    }],
    ['attribute' => 'survey_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->survey_date);
        }],
    ['attribute' => 'mpp_name'],
    ['attribute' => 'state_code', 
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->stateCode, 'state_name');
        },
        'visible' => false, 'filter' => false],
    ['attribute' => 'district_code', 
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->districtCode, 'district_name');
        },
        'visible' => false, 'filter' => false],
    ['attribute' => 'sub_district_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->subDistrictCode, 'sub_district_name');
        },
        'visible' => false, 'filter' => false],
    ['attribute' => 'village_code', 
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->villageCode, 'village_name');
        },
        'visible' => false, 'filter' => false],
    ['attribute' => 'hamlet_code', 
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->hamletCode, 'hamlet_name');
        },
        'visible' => false, 'filter' => false],
    ['attribute' => 'pincode', 'visible' => false, 'filter' => false],
    ['attribute' => 'no_of_family_gen'],
    ['attribute' => 'no_of_family_obc'],
    ['attribute' => 'no_of_family_st'],
    ['attribute' => 'no_of_family_sc'],
    ['attribute' => 'no_of_family_other'],
    ['attribute' => 'no_of_family_total'],
    ['attribute' => 'milch_animal_cow_cnt'],
    ['attribute' => 'milch_animal_buff_cnt'],
    ['attribute' => 'milch_animal_country_cow_cnt'],
    ['attribute' => 'milch_animal_cnt_total'],
    ['attribute' => 'non_milch_animal_cow_cnt'],
    ['attribute' => 'non_milch_animal_buff_cnt'],
    ['attribute' => 'non_milch_animal_country_cow_cnt'],
    ['attribute' => 'non_milch_animal_cnt_total'],
    ['attribute' => 'cow_milk_volume'],
    ['attribute' => 'buff_milk_volume'],
    ['attribute' => 'total_milk_volume'],
    ['attribute' => 'nos_of_pouring_members'],
    ['attribute' => 'per_day_milk_sales_volume'],
    ['attribute' => 'expected_pourer_count'],
    ['attribute' => 'expected_milk_volume'],
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
