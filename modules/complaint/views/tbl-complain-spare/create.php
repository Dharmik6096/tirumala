<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\complaint\models\TblComplainSpare */

$this->title = Yii::t('app', 'Create Tbl Complain Spare');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Complain Spares'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-complain-spare-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
