<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblVspPayment */

$this->title = $model->vsp_payment_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Vsp Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-vsp-payment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->vsp_payment_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->vsp_payment_code], [
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
            'vsp_payment_code',
            'society_code',
            'prev_due',
            'current_amount',
            'adjust_amount',
            'net_payable',
            'adjust_remark',
            'payment_cycle_applicabilty_code',
            'payment_date',
        ],
    ]) ?>

</div>
