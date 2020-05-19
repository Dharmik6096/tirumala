<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblBmcMilkDispatch */

$this->title = $model->bmc_milk_dispatch_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bmc Milk Dispatches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-bmc-milk-dispatch-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->bmc_milk_dispatch_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->bmc_milk_dispatch_code], [
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
            'bmc_milk_dispatch_code',
            'challan_no',
            'transaction_date',
            'from_date',
            'from_shift_code',
            'to_date',
            'to_shift_code',
            'destination_type',
            'destination_code',
            'vehicle_code',
            'trip_code',
            'driver_name',
            'driver_contact_no',
            'authorizer_name',
            'vehicle_in_time',
            'vehicle_out_time',
            'remarks',
            'gross_weight',
            'tare_weight',
            'is_last_destination',
            'purchase_rate_code',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
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
