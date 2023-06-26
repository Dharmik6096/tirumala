<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Member Credit Limit Transaction'));
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        
    <div class="grid-search">
        <?php $data = $dataProvider->getModels(); ?>
        <div class="" style='padding-top:6px;'>
            <b><?php echo Yii::t('app','Union');?></b> :: <?php echo (!empty($data)) ? $data[0]->memberCreditLimit->unionCode->union_name:''; ?>
            <br/><b><?php echo Yii::t('app','Society');?></b> :: <?php echo (!empty($data)) ? $data[0]->memberCreditLimit->dcsCode->dcs_name:''; ?>
            <b><?php echo Yii::t('app','Member');?></b> :: <?php echo (!empty($data)) ? $data[0]->memberCreditLimit->memberCode->member_name:''; ?>
        </div>
    </div>
<?php

$attribute = [
    ['attribute' => 'old_value','value'=>'old_value','filter'=>false,'format' => Yii::$app->general->CurrencyFormat()],
    ['attribute' => 'new_value','value'=>'new_value','filter'=>false,'format' => Yii::$app->general->CurrencyFormat()],
    ['attribute' => 'balance','value'=>'balance','filter'=>false,'format' => Yii::$app->general->CurrencyFormat()],
    ['attribute' => 'transaction_type','value'=>function($model){ return ($model->new_value >=0) ? 'Addition':'Deduction'; },'filter'=>false],
//    ['attribute' => 'union_code','value'=>'unionCode.union_name','filter'=>false],
//    ['attribute' => 'dcs_code','value'=>'dcsCode.dcs_name','filter'=>false],
//    ['attribute' => 'member_code','value'=>'member_code','filter'=>false,'label'=>Yii::t('app', 'Member Code')],
//    ['attribute' => 'member_code','value'=>'memberCode.member_name','filter'=>false],
//    ['attribute' => 'balance','value'=>'balance','filter'=>false],
];

$grid_option = [
    'id' => 'union-credit-limit-grid',
    'attributes' => $attribute,
    'active_column' => false,
//    'actions' => [
//        'member-credit-limit-transaction' => function ($url, $model) {
//            $options = ['data-name' => $model->member_credit_limit_code, 'data-val' => $model->member_credit_limit_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Credit Limit Transaction', 'class' => ''];
//            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/payment/tbl-member-credit-limit/view', 'member_credit_limit_code' => $model->member_credit_limit_code], $options);
//        },
//    ]
];

Yii::$app->grid->bind($dataProvider, $model, $grid_option);
?>
    </div>
</div>