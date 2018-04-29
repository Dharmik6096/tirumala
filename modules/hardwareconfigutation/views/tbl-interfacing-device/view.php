<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\hardwareconfigutation\models\TblInterfacingDevice */

$this->title = $model->device_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Interfacing Devices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-interfacing-device-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->device_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->device_code], [
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
            'device_code',
            'baud_rate',
            'bit_rate',
            'created_at',
            'created_by',
            'deleted_at',
            'deleted_by',
            'device_name',
            'device_type',
            'discard_char',
            'end_char',
            'flg_sentbox_entry',
            'incoming_data_type',
            'is_active:boolean',
            'is_delete:boolean',
            'is_snf:boolean',
            'length',
            'parity',
            'reading_type',
            'reg_expression',
            'split_char',
            'start_char',
            'stop_bit',
            'sync_status',
            'sync_timestamp',
            'tare',
            'updated_at',
            'updated_by',
            'device_manufacturer_id',
            'union_code',
        ],
    ]) ?>

</div>
