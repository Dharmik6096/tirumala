<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\transporter\models\TblGateEntry */

$this->title = $model->gate_entry_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Gate Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-gate-entry-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->gate_entry_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->gate_entry_code], [
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
            'gate_entry_code',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            'dcs_code',
            'route_code',
            'transporter_code',
            'vehicle_code',
            'date_time_of_collection',
            'shift_code',
            'define_arrival_time',
            'actual_arrival_time',
            'grace_time',
            'late_by_time',
            'responsibility_code',
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
