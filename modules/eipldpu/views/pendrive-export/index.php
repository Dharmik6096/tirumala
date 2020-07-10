<?php

use yii\grid\GridView;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Master Export'));
$this->params['menu'][] = Yii::$app->controls->add('Master Export');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'file_log_code',
                'process_type',
                'data_type',
                'dpu_type',
                'file_type',
                // 'file_path',
                // 'file_name',
                // 'file_name_download',
                // 'union_code',
                // 'plant_code',
                // 'mcc_plant_code',
                // 'bmc_code',
                // 'dcs_code',
                // 'ref_code',
                // 'created_at',
                // 'created_by',
                // 'updated_at',
                // 'updated_by',
                // 'originating_org_code',
                // 'originating_org_type',
                // 'originating_type',
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
        ?>
    </div>
</div>





