<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblUsers */

$this->title = Yii::t('app', 'Create Tbl Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
