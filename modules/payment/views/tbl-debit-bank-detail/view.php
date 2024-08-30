<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblDebitBankDetail */

$this->title = $model->debit_bank_detail_code;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Debit Bank Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-debit-bank-detail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->debit_bank_detail_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->debit_bank_detail_code], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'debit_bank_detail_code',
            'union_bank_payment_code',
            'union_code',
            'module_code',
            'module_name',
            'branch_name',
            'branch_code',
            'ifsc',
            'bank_account_no',
            'account_holder_name',
            'bank_email:email',
            'bank_mobile',
            'mobile_no',
            'email:email',
            'is_active',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_org_code',
            'originating_org_type',
            'originating_type',
            'x_col1',
            'x_col2',
            'x_col3',
            'x_col4',
            'x_col5',
        ],
    ]) ?>

</div>
