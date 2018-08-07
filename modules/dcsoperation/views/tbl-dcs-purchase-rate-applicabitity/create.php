<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity */

?>

    <?= $this->render('_form', [
        'model' => $model,'type' => 'create','purchaseRate' => $purchaseRate,'routes' => $routes,
    ]) ?>

