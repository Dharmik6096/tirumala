<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblBonusPayment */

$this->title = $model->bonus_payment_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bonus Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-bonus-payment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->bonus_payment_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->bonus_payment_code], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'bonus_payment_code',
            'bonus_payment_summary_code',
            'customer_type',
            'customer_code',
            'customer_name',
            'kg_fat',
            'kg_snf',
            'avg_fat',
            'avg_snf',
            'qty',
            'amount',
            'addition',
            'deduction',
            'net_payable',
            'status',
            'disburse_amount',
            'disburse_date',
            'payment_date',
            'bank_name',
            'bank_code',
            'branch_name',
            'branch_code',
            'ifsc',
            'bank_account_no',
            'beneficiary_name',
            'is_verified',
            'utr_no',
            'reference_no',
            'process_date',
            'reject_reason',
            'bank_status',
            'payment_transaction_code',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_org_code',
            'originating_org_type',
            'originating_type',
        ],
    ]) ?>

</div>
