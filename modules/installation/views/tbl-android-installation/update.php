<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\installation\models\TblAndroidInstallation */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Android Installation',
]) . $model->android_installation_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Android Installations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->android_installation_id, 'url' => ['view', 'id' => $model->android_installation_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-android-installation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
