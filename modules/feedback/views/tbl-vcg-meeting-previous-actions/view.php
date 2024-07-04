<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\feedback\models\TblVCGMeetingPreviousActions */

$this->title = $model->VCG_previous_actions_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Vcg Meeting Previous Actions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-vcgmeeting-previous-actions-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->VCG_previous_actions_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->VCG_previous_actions_id], [
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
            'VCG_previous_actions_id',
            'VCG_M_Id',
            'VCG_M_feedback_id',
            'VCG_M_MOM_id',
            'description',
            'type',
            'isclose',
            'remarks',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_org_code',
            'flg_sentbox_entry',
            'originating_type',
        ],
    ]) ?>

</div>
