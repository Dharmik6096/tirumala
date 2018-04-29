<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblSocietyVendor */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Society Vendor',
]) . $model->society_vendor_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Society Vendors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->society_vendor_code, 'url' => ['view', 'id' => $model->society_vendor_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-society-vendor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
