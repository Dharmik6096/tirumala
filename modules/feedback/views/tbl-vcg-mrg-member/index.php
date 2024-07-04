<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\feedback\models\TblVCGMRGMemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tbl Vcgmrg Members';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-vcgmrgmember-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tbl Vcgmrg Member', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'VCG_MRG_member_id',
            'mcc_plant_code',
            'bmc_code',
            'route_code',
            'dcs_code',
            // 'member_code',
            // 'member_tr_code',
            // 'wef_date',
            // 'end_date',
            // 'status',
            // 'type',
            // 'attachment_sign_key',
            // 'attachment_photo_key',
            // 'remark',
            // 'approved_at',
            // 'approved_by',
            // 'transaction_date',
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
