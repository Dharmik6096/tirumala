<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblDcsGeneralConfig */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Dcs General Config',
]) . $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Dcs General Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-dcs-general-config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
