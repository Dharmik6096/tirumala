<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'DPU Passwords'));
$this->params['menu'][] = Yii::$app->controls->import('dpu-password', $this);
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => 'create',
        ])
        ?>
    </div>
</div>
