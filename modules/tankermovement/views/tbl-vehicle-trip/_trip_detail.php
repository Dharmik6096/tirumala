<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$minDate = $trip->transaction_date;
$response = Yii::$app->general->getColumnName($tripDetail->source_org_type);
$sourceData = $tripDetail->{$response['rel'] . 'Source'};
$sourceValue = !empty($sourceData) ? $sourceData->{$response['name']} . '-' . strtoupper($tripDetail->source_org_type) : '';
$sourceCode = !empty($sourceData) ? $sourceData->{$response['ref_code']} : '';
$client_code = Yii::$app->session->get('eiplCode') == 'DODLA';
$currentDateTime = date('Y-m-d\TH:i:s');
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
                        $fieldOptions = ['type' => 'datetime-local', 'class' => 'first-input form-control', 'min' => date('Y-m-d\TH:i', strtotime($minDate))];
                        if ($client_code) {
                            $fieldOptions['value'] = $currentDateTime;
                            $fieldOptions['readonly'] = true;
                        }
                        ?>
                        <?= $form->field($tripDetail, $column_name)->textInput($fieldOptions) ?>
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
                            <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $tripDetail, 'save_data'); ?>

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
