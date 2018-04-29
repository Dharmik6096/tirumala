<?php

use yii\helpers\Html;

?>
<div class="tbl-asset-group-create">
    <div class="panel panel-main">
        <div class="panel-heading"><?= Yii::t('app', 'Financial Year') ?></div>

        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create'
        ])
        ?>

    </div>
</div>