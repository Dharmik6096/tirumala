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
    ['attribute' => 'MRG_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->MRG_date);
        }],
    ['attribute' => 'm_from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->m_from_date);
        }],
    ['attribute' => 'm_to_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->m_to_date);
        }],
    ['attribute' => 'from_time',
        'value' => function($model) {
            return Yii::$app->controls->view_time($model->from_time);
        }, 'filter' => false],
    ['attribute' => 'to_time',
        'value' => function($model) {
            return Yii::$app->controls->view_time($model->to_time);
        }, 'filter' => false],
    ['attribute' => 'attandance_count'],
    ['attribute' => 'pib_office_code'],
    ['attribute' => 'pib_office_name', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->pibOfficeCode, 'name');
    }, 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'area_office_code'],
    ['attribute' => 'area_office_name', 'value' => function($model) {
        return Yii::$app->general->getforeignkey($model->areaOfficeCode, 'name');
    }, 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'status', 'filter' => Yii::$app->dropdown->dropdownfilterStatic('vcg_mrg_meeting_status', $searchModel, 'status')],
    ['attribute' => 'remarks'],
];

$grid_option = [
    'id' => 'vcg-mrg-member-grid',
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
