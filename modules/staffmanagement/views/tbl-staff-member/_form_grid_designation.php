<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Html;

?>
<?php

$attribute = [
    ['attribute' => 'designation_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->designationCode, 'designation_name');
        }, 'filter' => FALSE],
    [
        'attribute' => 'tenure_from_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'width' => '200px',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->tenure_from_date);
        }, 'filter' => FALSE],
    [
        'attribute' => 'tenure_to_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'width' => '200px',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->tenure_to_date);
        }, 'filter' => FALSE],
    ['attribute' => 'remark', 'filter' => FALSE],
];
$grid_option = [
    'id' => 'staff-member-list',
    'attributes' => $attribute,
    'active_column' => false,
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>