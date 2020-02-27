<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'VSP Outstanding'));
$this->params['menu'][] = Yii::$app->controls->add('VSP Outstanding');
$this->params['menu'][] = Yii::$app->controls->import('vsp-outstanding', $this);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading"><?= $this->title; ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
    </div>
</div>