<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\welfarescheme\models\TblSchemeDocumentMappingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tbl Scheme Document Mappings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-scheme-document-mapping-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tbl Scheme Document Mapping', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'mapping_id',
            'scheme_id',
            'doc_id',
            'is_mandate',
            'union_code',
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
