<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\clienterp\models\TblClientErpApiLog */

$this->title = $model->log_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Client Erp Api Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-client-erp-api-log-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->log_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->log_id], [
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
            'log_id',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            'end_point',
            'request_url:url',
            'request_desc',
            'txn_type',
            'date1',
            'date2',
            'desc1',
            'desc2',
            'request_header',
            'request_payload',
            'response_payload',
            'request_timestamp',
            'response_timestamp',
            'status_code',
            'status_type',
            'status_response',
            'status_message',
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
