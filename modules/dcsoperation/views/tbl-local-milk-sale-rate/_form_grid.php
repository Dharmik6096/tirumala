<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
?>

<div class="grid-search clearfix">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    [
        'attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
    ['attribute' => 'rate', 'value' => 'rate',],
];

$grid_option = [
    'id' => 'loca-milk-sale-rate',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>