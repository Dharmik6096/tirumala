<?php

use yii\helpers\Html;
?>
<div class="tbl-collection-point-update">
    <div class="panel panel-default panel-main">
        <div class="panel-heading"><?= Yii::t('app', 'Collection Point') ?></div>
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