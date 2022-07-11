<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\transporter\models\TblGateEntry */

$this->title = Yii::t('app', 'Create Tbl Gate Entry');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Gate Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-gate-entry-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
