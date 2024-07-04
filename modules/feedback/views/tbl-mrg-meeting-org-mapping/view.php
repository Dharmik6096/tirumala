<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\feedback\models\TblMRGMeetingOrgMapping */

$this->title = $model->MRG_org_map_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Mrg Meeting Org Mappings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-mrgmeeting-org-mapping-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->MRG_org_map_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->MRG_org_map_id], [
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
            'MRG_org_map_id',
            'MRG_M_Id',
            'mcc_plant_code',
            'bmc_code',
            'route_code',
            'dcs_code',
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
