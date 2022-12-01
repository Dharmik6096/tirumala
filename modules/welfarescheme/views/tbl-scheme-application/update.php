<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\welfarescheme\models\TblSchemeApplication */

$this->title = 'Update Tbl Scheme Application: ' . $model->application_id;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Scheme Applications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->application_id, 'url' => ['view', 'id' => $model->application_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tbl-scheme-application-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
