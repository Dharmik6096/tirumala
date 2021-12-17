<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblLoanProductSaleDetails */

$this->title = $model->sale_detail_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Loan Product Sale Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-loan-product-sale-details-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->sale_detail_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->sale_detail_code], [
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
            'sale_detail_code',
            'dcs_code',
            'union_code',
            'member_code',
            'product_code',
            'sale_date_time',
            'amount',
            'entry_type',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'send_status',
            'response_datetime',
            'picked_datetime',
            'resp_desc',
            'data_inserted_from',
            'txfarmer_id',
        ],
    ]) ?>

</div>
