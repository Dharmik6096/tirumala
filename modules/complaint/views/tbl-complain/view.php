<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\complaint\models\TblComplain */

$this->title = $model->complain_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Complains'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-complain-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->complain_code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->complain_code], [
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
            'complain_code',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            'dcs_code',
            'location_type',
            'complain_for',
            'complain_type_code',
            'complain_datetime',
            'complain_assignment_datetime',
            'asset_code',
            'complain_problem_code',
            'serial_number',
            'new_serial_no',
            'contact_person',
            'mobile_no',
            'complain_status',
            'complain_status_datetime',
            'user_code',
            'physical_damage',
            'spare_required',
            'affects_data',
            'lat_long',
            'location_details',
            'remarks',
            'entry_type',
            'resolved_status',
            'resolved_datetime',
            'resolved_remarks',
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
