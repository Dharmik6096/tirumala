<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\complaint\models\TblComplainActivity */

$this->title = Yii::t('app', 'Create Tbl Complain Activity');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Complain Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-complain-activity-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
