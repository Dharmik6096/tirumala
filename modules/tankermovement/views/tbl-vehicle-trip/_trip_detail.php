<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$minDate = $trip->transaction_date;
$rel = Yii::$app->general->getDestRelation($tripDetail->source_org_type);
$sourceOrgType = strtolower($tripDetail->source_org_type);
if ($sourceOrgType == 'bmc') {
    $att = 'bmc_name';
} elseif ($sourceOrgType == 'vendor') {
    $att = 'customer_name';
} elseif ($sourceOrgType == 'party') {
    $att = 'party_name';
} else {
    $att = 'name';
}
$sourceValue = !empty($rel) ? Yii::$app->general->getforeignkey($tripDetail->{$rel . 'Source'}, $att) . '-' . strtoupper($tripDetail->source_org_type) : '';
$sourceCode = !empty($rel) ? ($sourceOrgType == 'party' ? 'N/A' : Yii::$app->general->getforeignkey($tripDetail->{$rel . 'Source'}, 'ref_code')) : '';
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
                                'enableAjaxValidation' => true,
                                'enableClientValidation' => true,
                                'options' => ['class' => 'panel panel-body'],
                                'action' => Url::to(['/tankermovement/tbl-vehicle-trip/gate-process']),
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
                            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
                            <?= Html::resetButton('Reset', ['class' => 'btn btn-danger']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$script = <<<JS
$(document).ready(function () {
    $('#trip-detail-form').on('beforeSubmit', function (event) {
        event.preventDefault();

        var form = $(this);
        if (form.find('.has-error').length) {
            return false;
        }

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
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
                console.error("AJAX error:", xhr.responseText);
            }
        });

        return false;
    });
});
JS;

$this->registerJs($script);
?>
