<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblPaymentCycle */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Payment Cycle',
]) . $model->payment_cycle_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Payment Cycles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->payment_cycle_code, 'url' => ['view', 'id' => $model->payment_cycle_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-payment-cycle-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
