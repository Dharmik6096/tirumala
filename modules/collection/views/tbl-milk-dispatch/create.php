<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkDispatch */

$this->title = Yii::t('app', 'Create Tbl Milk Dispatch');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Dispatches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-milk-dispatch-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
