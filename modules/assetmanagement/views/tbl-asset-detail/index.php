<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Asset Detail'));
$this->params['menu'][] = Yii::$app->controls->add('Asset Detail');
$this->params['menu'][] = Yii::$app->controls->add('Inward Asset', 'in-asset-transation');
//$this->params['menu'][] = Yii::$app->controls->add('Outward/In-Use Asset', 'out-asset-transation');
$this->params['menu'][] = Yii::$app->controls->add('Outward/In-Use Asset', 'asset-transaction');
$this->params['menu'][] = Yii::$app->controls->import('asset-detail', $this);
//$this->params['menu'][] = Yii::$app->controls->import('asset-detail-bom', $this, Yii::t('app', 'Asset Detail Bom Import'));
$this->params['menu'][] = Yii::$app->controls->import('asset-detail-bom', $this, Yii::t('app', 'Asset Detail Bom Import'), [], 'asset_detail_bom_1');

//$this->params['menu'][] = Yii::$app->controls->import('asset-detail-bom', $this);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?=
        $this->render('_form_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
    </div>
</div>
