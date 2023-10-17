<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblShiftTimeExceed */

$this->title = $model->shift_time_exceed_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Shift Time Exceeds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-shift-time-exceed-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->shift_time_exceed_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->shift_time_exceed_code], [
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
            'shift_time_exceed_code',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            'dcs_code',
            'org_type',
            'org_code',
            'date_time_of_collection',
            'shift_code',
            'standard_time',
            'exceed_time',
            'remarks',
            'status',
            'status_datetime',
            'status_by',
            'status_remarks',
            'x_col1',
            'x_col2',
            'x_col3',
            'x_col4',
            'x_col5',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_org_code',
            'originating_org_type',
            'originating_type',
        ],
    ]) ?>

</div>
