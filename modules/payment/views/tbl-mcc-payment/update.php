<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblVspPayment */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Vsp Payment',
]) . $model->vsp_payment_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Vsp Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->vsp_payment_code, 'url' => ['view', 'id' => $model->vsp_payment_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-vsp-payment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
