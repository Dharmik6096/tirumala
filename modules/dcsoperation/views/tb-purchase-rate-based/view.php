<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblPurchaseRateBased */

$this->title = $model->rate_detail_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Purchase Rate Baseds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-purchase-rate-based-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->rate_detail_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->rate_detail_id], [
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
            'rate_detail_id',
            'created_at',
            'deleted_at',
            'end_range',
            'deduction_type',
            'ref_type',
            'fixed_point',
            'value',
            'kg_rate',
            'flg_sentbox_entry',
            'quality_param',
            'milk_quality_type_code',
            'start_range',
            'sync_status',
            'sync_timestamp',
            'updated_at',
            'milk_type_code',
            'created_by',
            'purchase_rate_code',
            'updated_by',
            'deleted_by',
            'is_active',
            'is_delete',
        ],
    ]) ?>

</div>
