<div class="panel panel-main">
    <div class="panel-heading"><?php echo Yii::t('app', 'Head Load'); ?></div>
    <?=
    $this->render('_form', [
        'model' => $model, 'type' => 'edit', 'transaction' => $transaction, 'jsonEncoded' => $jsonEncoded
    ])
    ?>
</div>