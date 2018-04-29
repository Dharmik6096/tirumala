<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblTransporterPayment */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Transporter Payment',
]) . $model->transporter_payment_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Transporter Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->transporter_payment_code, 'url' => ['view', 'id' => $model->transporter_payment_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-transporter-payment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
