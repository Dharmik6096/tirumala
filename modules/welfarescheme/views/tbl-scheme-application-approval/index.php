<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\welfarescheme\models\TblSchemeApplicationApprovalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tbl Scheme Application Approvals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-scheme-application-approval-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tbl Scheme Application Approval', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'app_approval_id',
            'application_id',
            'level',
            'user_code',
            'approval_mode',
            // 'approved_value',
            // 'application_status',
            // 'status_date',
            // 'status_by',
            // 'status_remarks',
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
