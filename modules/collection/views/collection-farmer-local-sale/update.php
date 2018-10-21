<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\CollectionFarmerLocalSale */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Collection Farmer Local Sale',
]) . $model->dtdate;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collection Farmer Local Sales'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dtdate, 'url' => ['view', 'dtdate' => $model->dtdate, 'sampleno' => $model->sampleno, 'shift' => $model->shift, 'vlccid' => $model->vlccid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="collection-farmer-local-sale-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
