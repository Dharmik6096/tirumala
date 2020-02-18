<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\vsp\models\TblHeadLoadTransaction */

$this->title = $model->head_load_transaction_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Head Load Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-head-load-transaction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->head_load_transaction_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->head_load_transaction_code], [
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
            'head_load_transaction_code',
            'created_at',
            'deleted_at',
            'flg_sentbox_entry',
            'from_km',
            'from_qty',
            'is_delete',
            'sync_status',
            'sync_timestamp',
            'to_km',
            'to_qty',
            'updated_at',
            'value',
            'created_by',
            'deleted_by',
            'head_load_code',
            'updated_by',
        ],
    ]) ?>

</div>
