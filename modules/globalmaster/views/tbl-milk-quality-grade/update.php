<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\globalmaster\models\TblMilkQualityGrade */
?>
<div class="tbl-dcs-types-update">
    <div class="panel panel-default panel-main">
        <div class="panel-heading"><?= Yii::t('app', 'Milk Quality Grade') ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model, 'type' => 'edit', 'milkType' => $milkType, 'modelMilk' => $modelMilk
            ])
            ?>
        </div>
    </div>
</div>
