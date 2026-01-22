<?php

use app\components\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
?>

<div id="ProvisionalReroute">
    <div class="modal modal-default fade" id="ProvisionalModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×  </button>
                    <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Re Route Provisional Process') ?></h4>
                </div>

                <div class='row pad-10'>
                    <div class="col-sm-12">
                        <?php
                        $form = ActiveForm::begin(['options' => [
                                        'class' => 'form-group popup-form',
                                        'id' => 'reroute-form',
                                    ],
                        ]);
                        ?>
                        <div class="col-sm-4 remarks">
                            <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="modal-footer mt10 col-sm-12">
                            <div class="col-md-12 top-bottom-15 padding-50">
                                <?= Html::button(Yii::t('app', 'Re-Route'), ['class' => 'btn-login btn btn-primary apply-shortcut re-route', 'value' => 'reroute']) ?>
                                <?= Html::resetButton('Reset', ['class' => 'btn-login btn btn-primary']) ?>
                            </div>
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
$(document).on('click', '.re-route', function() {
    $('.error-summary').empty().hide();
     $('.help-block').empty();
    $('.form-group').removeClass('has-error');
    $('#ProvisionalModal').modal('hide');
    $('.set_operation').val($(this).attr('value'));
    $('.saveBtn').trigger('click');
});
";
$this->registerJs($script, View::POS_END, 'reroute');
?>