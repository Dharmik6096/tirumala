<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsaccounting\models\TblUnionBillHead */

$this->title = Yii::t('app', Yii::$app->label->title('create', 'Head Load'));
?>
<div class="tbl-union-bill-head-create">
    <div class="panel panel-default panel-main">
        <div class="panel-heading"><?= $this->title ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model, 'type' => 'create',
            ])
            ?>
        </div>
    </div>
</div>
