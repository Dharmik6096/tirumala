<?php
$this->title = Yii::$app->label->title('edit', 'Society Mapping');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?> for <?= $purchaseRate->purchase_rate_code ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model, 'type' => 'edit',
        ])
        ?>
    </div>
</div>