<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$minDate = $trip->transaction_date;
?>
<div class="modal modal-default fade" id="TripDetailModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Trip Detail') ?></h4>
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
                        <?= $form->field($tripDetail, $column_name)->textInput(['type' => 'datetime-local', 'min' => date('Y-m-d\TH:i', strtotime($minDate))]) ?>
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
        
                        $('.help-block').html('<ul><li>' + msg + '</li></ul>').closest('.form-group').addClass('has-error').show();
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
