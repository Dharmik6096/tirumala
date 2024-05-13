<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblUnionBankPayment */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Union Bank Payment',
]) . $model->union_bank_payment_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Union Bank Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->union_bank_payment_code, 'url' => ['view', 'id' => $model->union_bank_payment_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-union-bank-payment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
