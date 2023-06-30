<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
?>

<div class="grid-search">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'union_code','value'=>'unionCode.union_name','filter'=>false],
    ['attribute' => 'dcs_code','value'=>'dcsCode.dcs_name','filter'=>false],
    ['attribute' => 'member_code','value'=>'member_code','filter'=>false,'label'=>Yii::t('app', 'Member Code')],
    ['attribute' => 'member_code','value'=>'memberCode.member_name','filter'=>false],
    ['attribute' => 'balance','value'=>'balance','filter'=>false,'format' => Yii::$app->general->CurrencyFormat()],
];

$grid_option = [
    'id' => 'union-credit-limit-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'member-credit-limit-transaction' => function ($url, $model) {
            $options = ['data-name' => $model->member_credit_limit_code, 'data-val' => $model->member_credit_limit_code, 'data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Credit Limit Transaction', 'class' => ''];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/payment/tbl-member-credit-limit/view', 'member_credit_limit_code' => $model->member_credit_limit_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>