<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\details\models\TblContactDetails */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Contact Details',
]) . $model->detail_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Contact Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->detail_code, 'url' => ['view', 'id' => $model->detail_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-contact-details-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
