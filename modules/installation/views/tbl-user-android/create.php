<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\installation\models\TblUserAndroid */

$this->title = Yii::t('app', 'Create Tbl User Android');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl User Androids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-user-android-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
