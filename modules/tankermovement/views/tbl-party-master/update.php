<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblPartyMaster */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Party Master',
]) . $model->party_master_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Party Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->party_master_code, 'url' => ['view', 'id' => $model->party_master_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-party-master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
