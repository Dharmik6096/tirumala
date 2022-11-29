<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblAppLockConfig */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl App Lock Config',
]) . $model->config_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl App Lock Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->config_code, 'url' => ['view', 'id' => $model->config_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-app-lock-config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
