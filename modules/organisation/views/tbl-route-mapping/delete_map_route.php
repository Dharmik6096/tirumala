<?php
$this->title = Yii::t('app', 'Delete Map Route Source');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider, 'customer' => $customer]); ?>

        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_delete_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
        ?>
    </div>
</div>
