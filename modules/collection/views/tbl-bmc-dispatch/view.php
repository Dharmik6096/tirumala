<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblBmcDispatch */

$this->title = $model->bmc_dispatch_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bmc Dispatches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-bmc-dispatch-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->bmc_dispatch_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->bmc_dispatch_code], [
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
            'bmc_dispatch_code',
            'fat',
            'snf',
            'mbrt',
            'temprature',
            'milk_test',
            'alcohole_test',
            'vehicle_code',
            'vehicle_in_time',
            'vehicle_out_time',
            'destination_code',
            'destination_type',
            'actual_qty',
            'dispatch_qty',
            'dispatch_datetime',
            'dispatch_shift',
            'milk_type_code',
            'milk_quality_type_code',
            'bmc_code',
            'route_code',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
