<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblBranch */

$this->title = Yii::t('app', 'Sub Center');
?>
<div class="tbl-sub-center-create">
    <div class="panel panel-default panel-main">
        <div class="panel-heading"><?= Yii::t('app', 'Sub Center') ?></div>
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