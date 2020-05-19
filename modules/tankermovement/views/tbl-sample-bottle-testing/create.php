<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tankermovement\models\TblSampleBottleTesting */

$this->title = Yii::t('app', 'Create Tbl Sample Bottle Testing');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Sample Bottle Testings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-sample-bottle-testing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
