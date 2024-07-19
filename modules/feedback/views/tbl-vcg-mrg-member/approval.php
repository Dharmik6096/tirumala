<?php
$this->title = Yii::t('app', 'VCG/MRG Member Approval');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body hide-grid-settings">
        <?= $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>
        <div class="clearfix"></div>
        <?= $this->render('_approval_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'memberModel' => $memberModel,]); ?>
    </div>
</div>