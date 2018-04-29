
<div class="tbl-union-bill-head-update">
<div class="panel panel-main">
        <div class="panel-heading"><?= Yii::t('app', 'general union configuration') ?></div>

    <?= $this->render('_form', [
        'model' => $model,'type' => 'edit'
    ]) ?>
    </div>
</div>
