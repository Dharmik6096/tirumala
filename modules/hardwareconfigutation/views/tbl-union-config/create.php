<?php

$this->title = Yii::t('app', Yii::$app->label->title('create', 'general union configuration'));
?>
<div class="tbl-union-bill-head-create">
    <div class="panel panel-main">
        <div class="panel-heading"><?= $this->title ?></div>

    <?= $this->render('_form', [
        'model' => $model,'type' => 'create',
    ]) ?>
    </div>
</div>

