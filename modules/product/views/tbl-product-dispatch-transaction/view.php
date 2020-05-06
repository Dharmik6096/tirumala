<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductDispatchTransaction */

$this->title = $model->dispatch_transaction_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Product Dispatch Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-product-dispatch-transaction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->dispatch_transaction_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->dispatch_transaction_code], [
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
            'dispatch_transaction_code',
            'vendor_type',
            'vendor_code',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            'challan_no',
            'dispatch_date',
            'product_requisition_code',
            'requisition_transaction_code',
            'product_code',
            'status',
            'rate',
            'amount',
            'discount_amount',
            'dispatch_qty',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_org_code',
            'originating_org_type',
            'originating_type',
            'x_col1',
            'x_col2',
            'x_col3',
            'x_col4',
            'x_col5',
        ],
    ]) ?>

</div>
