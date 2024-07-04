<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\feedback\models\TblMRGMeetingFeedback */

$this->title = $model->MRG_M_feedback_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Mrg Meeting Feedbacks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-mrgmeeting-feedback-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->MRG_M_feedback_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->MRG_M_feedback_id], [
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
            'MRG_M_feedback_id',
            'MRG_M_Id',
            'month',
            'feedback_desc',
            'member_code',
            'type',
            'status',
            'action_taken',
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
