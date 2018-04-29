<?php

use yii\helpers\Html;
?>
<div class="tbl-banks-create">
    <div class="panel panel-main">
        <div class="panel-heading"><?= Yii::t('app', 'Installation Identity') ?></div>
            <?=
            $this->render('_form', [
                'model' => $model,
                'type' => 'create',
            ])
            ?>
    </div>
</div>