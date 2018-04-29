<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
?>

<div class="grid-search clearfix">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'member_code','value'=>'member_code',],
    ['attribute' => 'member_name','value'=>'memberCode.member_name',],
    [
        'attribute' => 'entry_type',
        'filter' => Html::activeDropDownList($searchModel, 'entry_type', $searchModel->entryType(),['class'=>'form-control','prompt'=>'Select']),
        'value' => function($model) {return ($model->entryType()[$model->entry_type]);}
    ],
    ['attribute' => 'quantity','value'=>'quantity',],
    ['attribute' => 'rate','format' => Yii::$app->general->CurrencyFormat(),],
    ['attribute' => 'discount','format' => Yii::$app->general->CurrencyFormat(),],
    ['attribute' => 'amount','format' => Yii::$app->general->CurrencyFormat(),],
    [
        'attribute' => 'payment_mode',
        'filter' => Html::activeDropDownList($searchModel, 'payment_mode', [1=>'Bank',0=>'Cash'],['class'=>'form-control','prompt'=>'Select']),
        'value' => function($model) {   return ($model->payment_mode==1)?'Bank':'Cash';}],
    ['attribute' => 'account_effect','value'=>'account_effect','visible' => false,'filter'=>false,],
    ['attribute' => 'cash','format' => Yii::$app->general->CurrencyFormat(),'visible' => false,'filter'=>false,],
    ['attribute' => 'coupon','value'=>'coupon','visible' => false,'filter'=>false,],
    ['attribute' => 'credit','value'=>'credit','visible' => false,'filter'=>false,],
    [
        'attribute' => 'date','visible' => false,'filter'=>false,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date);
}],
    ['attribute' => 'collection_point_code','value'=>'collection_point_code','visible' => false,'filter'=>false,],
        ['attribute' => 'member_code','value'=>'memberCode.member_name','visible' => false,'filter'=>false,],
        ['attribute' => 'milk_class','value'=>'milkClass.class_name','visible' => false,'filter'=>false,],
        ['attribute' => 'milk_type','value'=>'milkType.animal_type_name','visible' => false,'filter'=>false,],
        ['attribute' => 'shift_id','value'=>'shift.shift','visible' => false,'filter'=>false,],
];

$grid_option = [
    'id' => 'local-milk-sale',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>