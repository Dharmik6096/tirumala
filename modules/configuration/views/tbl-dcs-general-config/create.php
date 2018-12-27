<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblDcsGeneralConfig */

$this->title = Yii::t('app', 'Create Tbl Dcs General Config');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Dcs General Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-dcs-general-config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
