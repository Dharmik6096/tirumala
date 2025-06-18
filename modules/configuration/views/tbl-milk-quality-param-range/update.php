<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblMilkQualityParamRange */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Milk Quality Param Range',
]) . $model->milk_quality_param_range_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Quality Param Ranges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->milk_quality_param_range_code, 'url' => ['view', 'id' => $model->milk_quality_param_range_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-milk-quality-param-range-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
