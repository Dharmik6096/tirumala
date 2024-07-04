<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\feedback\models\TblVCGMeetingNonPouringMembersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Vcg Meeting Non Pouring Members');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-vcgmeeting-non-pouring-members-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Vcg Meeting Non Pouring Members'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'VCG_meeting_non_pouring_id',
            'VCG_M_Id',
            'month',
            'mcc_plant_code',
            'bmc_code',
            // 'member_code',
            // 'reason_id',
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
