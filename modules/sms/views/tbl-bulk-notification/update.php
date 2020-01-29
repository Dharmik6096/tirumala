<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sms\models\TblBulkNotification */

$this->title = 'Update Tbl Bulk Notification: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Bulk Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->bulk_notification_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tbl-bulk-notification-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
