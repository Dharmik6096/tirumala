<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\welfarescheme\models\TblSchemeDocumentMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tbl Scheme Document Masters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-scheme-document-master-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tbl Scheme Document Master', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'doc_id',
            'doc_group',
            'doc_name',
            'doc_ext',
            'is_active',
            // 'union_code',
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
