<?php
$this->title = Yii::t('app', 'Scheme Rate Applicability Approval');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_approval_search', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>

        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_approval_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
        ?>
    </div>
</div>

