<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollection */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Milk Collection',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Collections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->milk_collection_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-milk-collection-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
