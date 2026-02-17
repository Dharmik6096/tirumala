<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

?>
<div class="modal modal-default fade" id="CloseTripModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Close Trip') ?> (<?= $model->trip_code ?>)</h4>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'close-trip-form',
                        'options' => ['class' => 'panel panel-body'],
                    ]);
                    ?>

                    <div class="col-sm-12">
                        <?= $form->field($model, 'force_close_remarks')->textInput(['rows' => 3])->label(Yii::t('app', 'Force Close Remarks')) ?>
                    </div>

                    <div class="modal-footer mt10 col-sm-12">
                        <div class="col-md-12">
                            <?= Yii::$app->controls->save(Yii::t('app', 'Save'), $model, 'save_data'); ?>
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
        var formData = $('#close-trip-form').serialize();
        var formUrl = '" . Url::to(['/tankermovement/tbl-vehicle-trip/close-trip', 'id' => $model->vehicle_trip_code]) . "';
        $.ajax({
            url: formUrl,
            type: 'POST',
            data: formData,
            success: function (response) {
                var obj = response;
                if (typeof response === 'string') {
                    obj = JSON.parse(response);
                }
                if (obj.status == 'success') {
                    $('#CloseTripModal').modal('hide');
                    $.pjax.reload({container: '#vehicle-trip-list'});
                    bootbox.alert(\"<div class='row'><div class='col-sm-12'><div class='bg-info'><i class='fa fa-info'></i></div><span>\" + obj.msg + \"</span></div></div>\");
                } else {
                    bootbox.alert(\"<div class='row'><div class='col-sm-12'><div class='bg-danger'><i class='fa fa-times'></i></div><span>\" + obj.msg + \"</span></div></div>\");
                }
            },
            error: function (xhr) {
                console.log('Error:', xhr);
            }
        });

        return false;
    });
});
";

$this->registerJs($script, View::POS_END, 'close-trip-js');
?>