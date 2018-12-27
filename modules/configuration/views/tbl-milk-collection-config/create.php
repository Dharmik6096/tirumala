<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblMilkCollectionConfig */

$this->title = Yii::t('app', 'Create Tbl Milk Collection Config');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Collection Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-milk-collection-config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
