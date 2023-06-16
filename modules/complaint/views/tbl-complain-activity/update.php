<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\complaint\models\TblComplainActivity */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Complain Activity',
]) . $model->complain_activity_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Complain Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->complain_activity_code, 'url' => ['view', 'id' => $model->complain_activity_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-complain-activity-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
