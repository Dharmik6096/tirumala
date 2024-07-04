<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\feedback\models\TblVCGMeetingMOMSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Vcg Meeting Moms');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-vcgmeeting-mom-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Vcg Meeting Mom'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'VCG_MOM_id',
            'VCG_M_Id',
            'month',
            'concern',
            'member_code',
            // 'mom_type_id',
            // 'discussions',
            // 'status',
            // 'remarks_actions',
            // 'process_type',
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
