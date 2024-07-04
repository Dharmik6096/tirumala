<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\feedback\models\TblMRGMeetingMaster */

$this->title = $model->MRG_M_Id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Mrg Meeting Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-mrgmeeting-master-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->MRG_M_Id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->MRG_M_Id], [
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
            'MRG_M_Id',
            'MRG_M_code',
            'MRG_date',
            'from_time',
            'to_time',
            'm_from_date',
            'm_to_date',
            'attandance_count',
            'pib_office_code',
            'area_office_code',
            'remarks',
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
