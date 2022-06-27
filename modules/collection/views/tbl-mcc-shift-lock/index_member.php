<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'MCC Shift Lock (Member)'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <?=
        $this->render('_form_grid_member', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
    </div>
</div>
