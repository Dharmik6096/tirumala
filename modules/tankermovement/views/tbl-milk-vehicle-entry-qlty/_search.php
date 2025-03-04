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
        <?= Yii::$app->dropdown->federation_union($searchModel, $form, 'union_code', 'Union', ($dataProvider->getCount() == 0) ? true : false); ?>
    </div>
    <div class="col-sm-2 filldata">
        <?php echo Html::hiddenInput('trip_status', 'milk_entry_qlty', ['id' => 'tblmilkvehicleentryqltysearch-trip_status']); ?>
        <?= Yii::$app->dropdown->vehicleOpenTripDetail($searchModel, $form, 'tblmilkvehicleentryqltysearch-union_code,tblmilkvehicleentryqltysearch-trip_status', 'trip_code', $searchModel->getAttributeLabel('trip_code'), false, '', ($dataProvider->getCount() > 0) ? true : false); ?>
    </div>
    <?php if ($dataProvider->getCount() == 0) { ?>
        <div class="form-group padding_top_20">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary search']); ?>
        </div>
    <?php } else if ($dataProvider->getCount() > 0) { ?>
        <div class="col-sm-2">
            <?= $form->field($searchModel, 'parsing_no')->textInput(['disabled' => true])->label(); ?>
        </div>
    <?php } ?>

</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    $('#tblmilkvehicleentryqltysearch-trip_code').change(function() {
        console.log('Trip code changed:', $(this).val());
        $('#hidden-trip-code').val($(this).val());
    });
";
$this->registerJs($script, View::POS_END, 'milk-vehicle-entry-qlty-search');
?>