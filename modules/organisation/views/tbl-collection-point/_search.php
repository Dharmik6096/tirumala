<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblCollectionPointSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]);
?>
<div class="col-sm-2">
    <?php Yii::$app->dropdown->federation($model, $form, 'federation_code', false); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->union($model, $form, 'tblcollectionpointsearch-federation_code', 'union_code', false); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->depend_dropdown('dcs', $model, $form, 'tblcollectionpointsearch-union_code'); ?>    
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->depend_dropdown('sub-center', $model, $form, 'tblcollectionpointsearch-dcs_code'); ?>    
</div>
<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
<?php ActiveForm::end(); ?>