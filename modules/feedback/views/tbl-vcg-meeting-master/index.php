<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\feedback\models\TblVCGMeetingMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Vcg Meeting Masters');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-vcgmeeting-master-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Vcg Meeting Master'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'VCG_M_Id',
            'VCG_M_code',
            'VCG_date',
            'from_time',
            'to_time',
            // 'mcc_plant_code',
            // 'bmc_code',
            // 'route_code',
            // 'dcs_code',
            // 'attandance_count',
            // 'attachment_code',
            // 'route_supervisor_code',
            // 'pib_office_code',
            // 'status',
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
