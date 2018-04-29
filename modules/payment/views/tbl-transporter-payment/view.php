<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblTransporterPayment */

$this->title = $model->transporter_payment_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Transporter Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-transporter-payment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->transporter_payment_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->transporter_payment_code], [
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
            'transporter_payment_code',
            'transporter_code',
            'total_vehicle',
            'coll_qty',
            'coll_kg_fat',
            'coll_kg_snf',
            'disp_qty',
            'disp_kg_fat',
            'disp_kg_snf',
            'rec_qty',
            'rec_kg_fat',
            'rec_kg_snf',
            'cd_qty_diff',
            'cd_kg_fat_diff',
            'cd_kg_snf_diff',
            'rd_qty_diff',
            'rd_kg_fat_diff',
            'rd_kg_snf_diff',
            'no_of_days',
            'total_amount',
            'total_deduction',
            'final_amount',
            'adjust_amount',
            'net_amount',
            'remarks:ntext',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'delete_at',
            'delete_by',
            'is_active',
        ],
    ]) ?>

</div>
