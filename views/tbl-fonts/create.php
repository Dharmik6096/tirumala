<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblFonts */

$this->title = Yii::t('app', 'Create Tbl Fonts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Fonts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-fonts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
