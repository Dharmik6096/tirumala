<?php

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
            'method' => 'get',
        ]);
?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($searchModel, $form, 'union_code', 'Union', ($dataProviderCount == 0) ? true : false); ?>
    </div>
    <div class="col-sm-2 filldata">
        <?php echo Html::hiddenInput('trip_status', 'milk_entry_qlty', ['id' => 'tblmilkvehicleentryqltysearch-trip_status']); ?>
        <?= Yii::$app->dropdown->vehicleOpenTripDetail($searchModel, $form, 'tblmilkvehicleentryqltysearch-union_code,tblmilkvehicleentryqltysearch-trip_status', 'trip_code', $searchModel->getAttributeLabel('trip_code'), false, '', ($dataProviderCount > 0) ? true : false); ?>
    </div>
    <?php if ($dataProviderCount == 0) { ?>
        <div class="col-sm-2 pt19">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary search']); ?>
        </div>
    <?php } else if ($dataProviderCount > 0) { ?>
        <div class="col-sm-2">
            <?= $form->field($searchModel, 'parsing_no')->textInput(['disabled' => true])->label(); ?>
        </div>
        <div class="col-sm-2 mt18">
            <?= Html::a('', ['/tankermovement/tbl-vehicle-trip/vertical-chart', 'trip_code' => $searchModel->trip_code], ['data-toggle' => 'tooltip', 'data-bs-placement' => 'right', 'title' => 'View Map', 'target' => '_blank', 'data-val' => $searchModel->trip_code, 'class' => 'fa fa-info-circle fs24']); ?>
        </div>
    <?php } ?>

</div>
<?php ActiveForm::end(); ?>