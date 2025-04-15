<?php

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
            'method' => 'get',
        ]);
$readonly = empty($searchModel->trip_code) ? FALSE : TRUE;
?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($searchModel, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2 filldata">
        <?php echo Html::hiddenInput('process', 'milk_entry_qlty_merge', ['id' => 'process']); ?>
        <?= Yii::$app->dropdown->vehicleOpenTripDetail($searchModel, $form, 'tblmilkvehicleentryqltymergesearch-union_code,process', 'trip_code', $searchModel->getAttributeLabel('trip_code'), false, '', $readonly); ?>
    </div>
    <?php if (empty($searchModel->parsing_no)) { ?>
        <div class="col-sm-2 pt19">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary search']); ?>
        </div>
    <?php } else if (!empty($searchModel->parsing_no)) { ?>
        <div class="col-sm-2">
            <?= $form->field($searchModel, 'parsing_no')->textInput(['disabled' => true, 'value' => $searchModel->parsing_no])->label(); ?>
        </div>
    <?php } ?>

</div>
<?php ActiveForm::end(); ?>