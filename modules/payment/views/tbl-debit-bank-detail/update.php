<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblDebitBankDetail */

$this->title = 'Update Tbl Debit Bank Detail: ' . $model->debit_bank_detail_code;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Debit Bank Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->debit_bank_detail_code, 'url' => ['view', 'id' => $model->debit_bank_detail_code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tbl-debit-bank-detail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
