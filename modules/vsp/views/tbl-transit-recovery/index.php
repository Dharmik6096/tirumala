<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Transit Recovery Import Data'));
$this->params['menu'][] = Yii::$app->controls->import('transit-recovery-import', $this, Yii::t('app', 'Transit Recovery Import'));
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