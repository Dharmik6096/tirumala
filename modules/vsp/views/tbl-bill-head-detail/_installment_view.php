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
    <?php //echo $this->render('_search', ['model' => $searchModel]);  ?>
</div>

<?php
$attribute = [
    ['attribute' => 'installement_cycle', 'filter' => false],
    ['attribute' => 'installment_amount', 'filter' => false],
    [
        'attribute' => 'installment_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->installment_date);
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'head-installment-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['view', 'id' => Yii::$app->request->get('id')]);
?>