<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\CollectionFarmerLocalSale */

$this->title = Yii::t('app', 'Create Collection Farmer Local Sale');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collection Farmer Local Sales'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-farmer-local-sale-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
