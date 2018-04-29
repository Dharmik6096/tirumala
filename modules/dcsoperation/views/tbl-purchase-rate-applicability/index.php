<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\dcsoperation\models\TblPurchaseRateApplicabilitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Purchase Rate Applicabitities');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-purchase-rate-applicability-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Purchase Rate Applicability'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'rate_app_code',
            'created_at',
            'created_by',
            'deleted_at',
            'deleted_by',
            // 'flg_sentbox_entry',
            // 'is_active:boolean',
            // 'is_delete:boolean',
            // 'sync_status',
            // 'sync_timestamp',
            // 'updated_at',
            // 'updated_by',
            // 'wef_date',
            // 'dcs_code',
            // 'purchase_rate_code',
            // 'shift_code',
            // 'union_code',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
