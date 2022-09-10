<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkTransfer */

$this->title = $model->milk_transfer_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Transfers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-milk-transfer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->milk_transfer_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->milk_transfer_code], [
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
            'milk_transfer_code',
            'transaction_id',
            'from_date',
            'from_shift',
            'to_date',
            'to_shift',
            'transfer_type',
            'union_code',
            'source_code',
            'destination_code',
            'vehicle_no',
            'fat',
            'snf',
            'qty',
            'temp',
            'remarks',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_type',
            'originating_org_code',
            'originating_org_type',
            'x_col1',
            'x_col2',
            'x_col3',
            'x_col4',
            'x_col5',
        ],
    ]) ?>

</div>
