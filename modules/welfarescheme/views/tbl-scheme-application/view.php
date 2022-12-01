<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeApplication */

$this->title = $model->application_id;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Applications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-scheme-application-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->application_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->application_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'application_id',
            'scheme_id',
            'member_code',
            'application_date',
            'min_pouring_day',
            'min_pouring_qty',
            'actual_pouring_day',
            'actual_pouring_qty',
            'remarks',
            'scheme_value',
            'approved_value',
            'application_status',
            'status_date',
            'status_by',
            'status_remarks',
            'dcs_code',
            'bmc_code',
            'mcc_plant_code',
            'plant_code',
            'union_code',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_type',
            'originating_org_code',
            'originating_org_type',
        ],
    ]) ?>

</div>
