<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sms\models\TblBulkNotification */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Bulk Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-bulk-notification-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->bulk_notification_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->bulk_notification_id], [
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
            'bulk_notification_id',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            'dcs_code',
            'member_code',
            'app_type',
            'login_type',
            'wef_date',
            'title',
            'message',
            'campaign_name',
            'created_at',
            'created_by',
            'receiver_type',
            'content_id',
            'status',
            'entry_datetime',
            'pickup_datetime',
            'response_datetime',
        ],
    ]) ?>

</div>
