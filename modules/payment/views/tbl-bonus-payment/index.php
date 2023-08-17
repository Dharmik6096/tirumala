<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\payment\models\TblBonusPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Bonus Payments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-bonus-payment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Bonus Payment'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'bonus_payment_code',
            'bonus_payment_summary_code',
            'customer_type',
            'customer_code',
            'customer_name',
            // 'kg_fat',
            // 'kg_snf',
            // 'avg_fat',
            // 'avg_snf',
            // 'qty',
            // 'amount',
            // 'addition',
            // 'deduction',
            // 'net_payable',
            // 'status',
            // 'disburse_amount',
            // 'disburse_date',
            // 'payment_date',
            // 'bank_name',
            // 'bank_code',
            // 'branch_name',
            // 'branch_code',
            // 'ifsc',
            // 'bank_account_no',
            // 'beneficiary_name',
            // 'is_verified',
            // 'utr_no',
            // 'reference_no',
            // 'process_date',
            // 'reject_reason',
            // 'bank_status',
            // 'payment_transaction_code',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            // 'originating_org_code',
            // 'originating_org_type',
            // 'originating_type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
