<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$minDate = $trip->transaction_date;
$rel = Yii::$app->general->getDestRelation($tripDetail->source_org_type);
$sourceOrgType = strtolower($tripDetail->source_org_type);
if ($sourceOrgType == 'bmc') {
    $att = 'bmc_name';
    $ref_code = 'ref_code';
} elseif ($sourceOrgType == 'vendor') {
    $att = 'customer_name';
    $ref_code = 'ref_code';
} elseif ($sourceOrgType == 'party') {
    $att = 'party_name';
    $ref_code = 'sap_vendor_code';
} else {
    $ref_code = 'ref_code';
    $att = 'name';
}
$sourceValue = !empty($rel) ? Yii::$app->general->getforeignkey($tripDetail->{$rel . 'Source'}, $att) . '-' . strtoupper($tripDetail->source_org_type) : '';
$sourceCode = !empty($rel) ? (Yii::$app->general->getforeignkey($tripDetail->{$rel . 'Source'}, $ref_code)) : '';
?>
<div class="modal modal-default fade" id="TripDetailModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?= strtoupper($actionType) . ' :: ' . $sourceCode . '/' . $sourceValue ?></h4>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'trip-detail-form',
                                'options' => ['class' => 'panel panel-body'],
                    ]);
                    ?>

                    <div class="col-sm-6">
                        <?php
                        $column_name = 'departure_time';
                        if ($actionType == 'gate-in') {
                            $column_name = 'arrival_time';
                        }
                        ?>
                        <?= $form->field($tripDetail, $column_name)->textInput(['type' => 'datetime-local', 'min' => date('Y-m-d\TH:i', strtotime($minDate)), 'class' => 'first-input form-control']) ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $column_name = 'out_remarks';
                        if ($actionType == 'gate-in') {
                            $column_name = 'in_remarks';
                        }
                        ?>
                        <?= $form->field($tripDetail, $column_name)->textInput() ?>
                    </div>

                    <?= Html::activeHiddenInput($tripDetail, 'vehicle_trip_detail_code'); ?>
                    <?= Html::hiddenInput('actionType', $actionType, ['id' => 'actionType']) ?>

                    <div class="modal-footer mt10 col-sm-12">
                        <div class="col-md-12">
                            <?php //Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
                            <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $tripDetail, 'save_data btn-login'); ?>

                            <?= Html::resetButton('Reset', ['class' => 'btn btn-danger btn-login']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$script = "
$(document).ready(function () {
    $('.save_data').on('click', function (event) {
        event.preventDefault();
        var formData = $('#trip-detail-form').serialize();
        var formUrl = '" . Url::to(['/tankermovement/tbl-vehicle-trip/gate-process']) . "';
        $.ajax({
            url: formUrl,
            type: 'POST',
            data: formData,
            success: function (response) {
                    if (response.status == 'error') {
                        var msg = '';
                        if (response.errors.departure_time == undefined) {
                            msg = response.errors.arrival_time ;
                        }else{
                            msg = response.errors.departure_time ;    
                        }
                        $('.first-input').closest('.form-group').addClass('has-error').find('.help-block').html('<ul><li>' + msg + '</li></ul>').show();
                    } else {
                        $('#TripDetailModal').modal('hide');
                        location.reload();
                    }
            },
            error: function (xhr) {
            }
        });

        return false;
    });
});
";

$this->registerJs($script, View::POS_END, 'vehicle-trip-detail1');
?>
