<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsaccounting\models\TblUnionBillHead */
?>
<div class="tbl-union-bill-head-update">
    <div class="panel panel-default panel-main">
        <div class="panel-heading"><?= Yii::t('app', 'Head Load') ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model, 'type' => 'edit'
            ])
            ?>
        </div>
    </div>
</div>