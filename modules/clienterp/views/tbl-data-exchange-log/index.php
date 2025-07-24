<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Data Exchange Logs'));
$this->params['menu'][] = Yii::$app->controls->add('Data Exchange Repush');
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php
            echo $this->render('_form_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]);
            ?>
        </div>
    </div>
</div>
