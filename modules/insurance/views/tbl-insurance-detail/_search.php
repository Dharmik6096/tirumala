<?php

use yii\helpers\Html;
use app\components\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\insurance\models\TblInsuranceDetailSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-insurance-detail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['publish-finalize'],
        'method' => 'get',
    ]); ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('insurance_master_list', $model, $form, 'form-group col-sm-2 padding-right-5'); ?> 
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
