<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\feedback\models\TblVCGMeetingFeedbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Vcg Meeting Feedbacks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-vcgmeeting-feedback-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Vcg Meeting Feedback'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'VCG_M_feedback_id',
            'VCG_M_Id',
            'month',
            'feedback_desc',
            'member_code',
            // 'type',
            // 'status',
            // 'action_taken',
            // 'remarks',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            // 'originating_org_code',
            // 'flg_sentbox_entry',
            // 'originating_type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
