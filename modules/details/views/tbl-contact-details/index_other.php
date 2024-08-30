<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Contact Detail Info.'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <?php echo $this->render('_search_other', ['model' => $searchModel]); ?>
        <div class="clearfix"></div>
        <?php echo $this->render('_form_grid_other', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]); ?>
    </div>
</div>
