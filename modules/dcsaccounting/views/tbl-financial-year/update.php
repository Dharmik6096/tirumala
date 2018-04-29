<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsaccounting\models\TblSubLedger */


?>
<div class="tbl-sub-ledger-update">
    <div class="panel panel-main">
        <div class="panel-heading"><?= Yii::t('app', 'Financial Year') ?></div>

        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'edit'
        ])
        ?>

    </div>
</div>