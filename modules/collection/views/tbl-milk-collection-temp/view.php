<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollectionTemp */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Collection Temps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-milk-collection-temp-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->milk_collection_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->milk_collection_code], [
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
            'milk_collection_code',
            'member_code',
            'dcs_code',
            'name',
            'mobile_no',
            'milk_type_code',
            'fat',
            'snf',
            'water',
            'qty',
            'rtpl',
            'amount',
            'auto_flag',
            'shift',
            'date_time_of_collection',
            'date_time_of_recieve',
            'village_code',
            'sample_no',
            'type_of_data_receive',
            'rate_code',
            'error_log',
            'ack',
            'soc_bmc_flag',
            'dt_date',
            'sms_status',
            'sms_msgid',
            'sms_mobile',
            'sms_errorlog',
            'sms_timestamp',
            'data_post_status',
            'clr',
            'status',
            'qty_mode',
            'qlty_time',
            'qty_time',
            'no_of_can',
            'milk_quality_type_code',
            'qlty_auto',
            'qty_auto',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'route_code',
            'bmc_code',
            'converted_qty',
            'is_approved',
        ],
    ]) ?>

</div>
