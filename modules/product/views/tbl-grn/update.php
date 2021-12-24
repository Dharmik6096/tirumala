<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblGrn */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Grn',
]) . $model->grn_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Grns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->grn_code, 'url' => ['view', 'id' => $model->grn_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-grn-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
