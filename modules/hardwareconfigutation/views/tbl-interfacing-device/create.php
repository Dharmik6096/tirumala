<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblBranch */

?>
<div class="tbl-banks-create">
    <div class="panel panel-main">
        <div class="panel-heading"><?= Yii::t('app', 'Interfacing Device') ?></div>
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
        ])
        ?>
    </div>
</div>
