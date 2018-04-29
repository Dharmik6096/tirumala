<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Sub Center');
?>
<div class="tbl-sub-center-update">
    <div class="panel panel-default panel-main">
        <div class="panel-heading"><?= Yii::t('app', 'Sub Center') ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'type' => 'edit',
            ])
            ?>
        </div>
    </div>
</div>