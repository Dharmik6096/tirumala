<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\feedback\models\TblMRGMeetingMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Mrg Meeting Masters');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-mrgmeeting-master-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Mrg Meeting Master'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'MRG_M_Id',
            'MRG_M_code',
            'MRG_date',
            'from_time',
            'to_time',
            // 'm_from_date',
            // 'm_to_date',
            // 'attandance_count',
            // 'pib_office_code',
            // 'area_office_code',
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
