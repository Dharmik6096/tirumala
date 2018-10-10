<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollectionTemp */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Milk Collection Temp',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Collection Temps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->milk_collection_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-milk-collection-temp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
