<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblPlantConversionVendorMapping */

$this->title = 'Create Tbl Plant Conversion Vendor Mapping';
$this->params['breadcrumbs'][] = ['label' => 'Tbl Plant Conversion Vendor Mappings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-plant-conversion-vendor-mapping-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
