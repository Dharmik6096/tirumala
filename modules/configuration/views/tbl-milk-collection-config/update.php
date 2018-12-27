<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblMilkCollectionConfig */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Milk Collection Config',
]) . $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Collection Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-milk-collection-config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
