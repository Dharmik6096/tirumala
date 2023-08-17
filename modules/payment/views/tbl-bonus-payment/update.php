<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblBonusPayment */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Bonus Payment',
]) . $model->bonus_payment_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bonus Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bonus_payment_code, 'url' => ['view', 'id' => $model->bonus_payment_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-bonus-payment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
