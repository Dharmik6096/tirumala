<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblTankerRateBased */

$this->title = Yii::t('app', 'Create Tbl Purchase Rate Based');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Purchase Rate Based'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-tanker-rate-based-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
