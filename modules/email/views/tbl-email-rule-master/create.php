<?php

use yii\helpers\Html;
?>
<div class="tbl-collection-point-create">
    <div class="panel panel-default panel-main">
        <div class="panel-heading"><?= Yii::t('app', 'Email Rule Master') ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'type' => 'create',
            ])
            ?>
        </div>
    </div>
</div>
