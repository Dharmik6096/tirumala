<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblDcsSearch */
/* @var $form yii\widgets\ActiveForm */
//$model->vendor_code = !empty(Yii::$app->session->get('UserCode')) ? Yii::$app->session->get('UserCode') : '';
?>
<?php
$this->title = Yii::$app->label->title('edit', 'IMEI Number');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
         <?= Html::activeHiddenInput($soc, 'route_code'); ?>
         <?= Html::activeHiddenInput($soc, 'vendor_code'); ?>
        <?php foreach ($models as $index => $model) { ?>
            <div class="col-sm-3">
                <?php $label= 'IMEI for '.$model->dcsCode->dcs_name; ?>
                <?= Html::activeHiddenInput($model, "[$index]dcs_code"); ?>
                <?= $form->field($model, "[$index]imei_no")->label($label); ?>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
            <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Html::button(Yii::t('app', 'Update'), ['class' => 'btn btn-primary', 'button' => 'save']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $script = "
   $('#tblsocietycodes-dcs_code').on('change',function(){
            var id = $(this).val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-dcs/get-imei-no']) . "',
                        data: 'id='+id,
                        success: function(data) {
                                var obj1 = $.parseJSON(data);
                                $('#tblsocietycodes-imei_no').val(obj1.code);
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
";
    $this->registerJs($script, View::POS_END, 'union-select');

    $script = "var delay=2000;";
    $this->registerJs($script, View::POS_HEAD, 'time-loader');
    