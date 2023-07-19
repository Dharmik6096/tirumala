<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\complaint\models\TblComplainSpare */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Complain Spare',
]) . $model->complain_spare_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Complain Spares'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->complain_spare_code, 'url' => ['view', 'id' => $model->complain_spare_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-complain-spare-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
