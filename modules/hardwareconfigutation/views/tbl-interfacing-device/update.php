<?php

use yii\helpers\Html;
?>
<div class="tbl-banks-update">
    <div class="panel panel-main">
        <div class="panel-heading"><?= Yii::t('app', 'Interfacing Device') ?></div>
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'edit',
        ])
        ?>
    </div>
</div>