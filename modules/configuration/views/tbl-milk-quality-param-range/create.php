<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblMilkQualityParamRange */

$this->title = Yii::t('app', 'Create Tbl Milk Quality Param Range');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Quality Param Ranges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-milk-quality-param-range-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
