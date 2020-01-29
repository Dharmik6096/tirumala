<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sms\models\TblBulkNotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tbl Bulk Notifications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-bulk-notification-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tbl Bulk Notification', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'bulk_notification_id',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            // 'dcs_code',
            // 'member_code',
            // 'app_type',
            // 'login_type',
            // 'wef_date',
            // 'title',
            // 'message',
            // 'campaign_name',
            // 'created_at',
            // 'created_by',
            // 'receiver_type',
            // 'content_id',
            // 'status',
            // 'entry_datetime',
            // 'pickup_datetime',
            // 'response_datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
