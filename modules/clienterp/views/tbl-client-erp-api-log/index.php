<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\clienterp\models\TblClientErpApiLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Client Erp Api Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-client-erp-api-log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Client Erp Api Log'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'log_id',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            // 'end_point',
            // 'request_url:url',
            // 'request_desc',
            // 'txn_type',
            // 'date1',
            // 'date2',
            // 'desc1',
            // 'desc2',
            // 'request_header',
            // 'request_payload',
            // 'response_payload',
            // 'request_timestamp',
            // 'response_timestamp',
            // 'status_code',
            // 'status_type',
            // 'status_response',
            // 'status_message',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            // 'originating_org_code',
            // 'originating_org_type',
            // 'originating_type',
            // 'x_col1',
            // 'x_col2',
            // 'x_col3',
            // 'x_col4',
            // 'x_col5',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
