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
    ['attribute' => 'dcs_payment_cycle_code'],
    [
        'attribute' => 'from_date',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }],
    [
        'attribute' => 'to_date',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }],
    [
        'attribute' => 'is_billing',
        'filter' => Html::activeDropDownList($searchModel, 'is_billing', [1=>'Yes',0=>'No'],['class'=>'form-control','prompt'=>'Select']),
        'value' => function($model) {
    
    return ($model->is_billing==1)?'Yes':'No';}],
    ['attribute' => 'from_shift','value'=>'from_shift','visible'=>false,'filter'=>false,],
    ['attribute' => 'interval_value','value'=>'interval_value','visible'=>false,'filter'=>false,],
    ['attribute' => 'lock_billing_process','value'=>'lock_billing_process','visible'=>false,'filter'=>false,],
    ['attribute' => 'to_shift','value'=>'to_shift','visible'=>false,'filter'=>false,],
];

$grid_option = [
    'id' => 'payment-dcs-cycle-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>