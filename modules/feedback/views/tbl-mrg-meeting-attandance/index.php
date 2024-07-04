<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\feedback\models\TblMRGMeetingAttandanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Mrg Meeting Attandances');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-mrgmeeting-attandance-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Mrg Meeting Attandance'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'MRG_M_attandance_id',
            'MRG_M_Id',
            'mcc_plant_code',
            'bmc_code',
            'route_code',
            // 'dcs_code',
            // 'member_code',
            // 'is_present',
            // 'attachment_code',
            // 'reason',
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
