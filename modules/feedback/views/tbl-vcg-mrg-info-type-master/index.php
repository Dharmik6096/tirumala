<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\feedback\models\TblVCGMRGInfoTypeMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Vcgmrg Info Type Masters');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-vcgmrginfo-type-master-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Vcgmrg Info Type Master'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'info_type_id',
            'info_type_desc',
            'info_type_desc_local',
            'type',
            'created_at',
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
