<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMACleaning */

$this->title = Yii::t('app', 'Create Tbl Macleaning');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Macleanings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-macleaning-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
