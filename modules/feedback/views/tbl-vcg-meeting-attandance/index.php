<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\feedback\models\TblVCGMeetingAttandanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Vcg Meeting Attandances');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-vcgmeeting-attandance-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Vcg Meeting Attandance'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'VCG_M_attandance_id',
            'VCG_M_Id',
            'mcc_plant_code',
            'bmc_code',
            'member_code',
            // 'is_present',
            // 'attachment_code',
            // 'remarks',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            // 'originating_type',
            // 'originating_org_code',
            // 'originating_org_type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
