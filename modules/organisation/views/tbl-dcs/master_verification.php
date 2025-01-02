<?php
$this->title = Yii::t('app', 'Bank Verification');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class=" large-search hidden-print">
            <?php echo $this->render('_search_verification', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>

        </div>
        <div class="clearfix"></div>
        <?php if (!empty(Yii::$app->session->getFlash('error'))) { ?>   
            <div class="alert alert-danger">
                <?= Yii::$app->session->getFlash('error'); ?>
            </div>
        <?php } ?>
        <?php
        echo $this->render('_verification_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
        ?>
    </div>
</div>
