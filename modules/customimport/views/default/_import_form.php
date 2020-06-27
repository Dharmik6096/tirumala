<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use zainiafzan\widget\Dropzone;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;

$url = Url::to(['get-fields-ops']);
$data = array_combine($data, $data);
$action = Url::to(['import-form-sort']);
$param = (!empty(Yii::$app->request->get('local_fields'))) ? Yii::$app->request->get('local_fields') : 'local_name';
$readonly = false;
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?php echo Yii::t('app', 'Import Data'); ?>
    </div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'import-gen',
                    'method' => 'get',
                    'action' => $action,
                    'validateOnBlur' => false,
        ]);
        ?>
        <?= $form->errorSummary($model); ?>
        <?= Html::hiddenInput('flag', $flag); ?>
        <?= Html::activeHiddenInput($model, 'required'); ?>
        <div class="row">
            <div class="col-sm-2">
                <?= $form->field($model, 'module_name')->dropDownList($data, ['prompt' => 'Select Module']); ?>
            </div>    
            <div class="clearfix"></div>
            <div class="col-sm-10 mt10" id="default_flds"></div>
            <div class="clearfix"></div>
            <div class="col-md-12 mt25">
                <div class="row">
                    <div class="col-sm-10 multiple">
                        <?= Yii::$app->controls->dualList($form, $model, 'fields', []); ?>
                    </div>

                </div>
            </div>
            <?= Html::activeHiddenInput($model, 'operation'); ?>

            <div class="clearfix"></div>
            <div class="col-sm-12 shortcut-main mt25" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save('Next', $model, 'next'); ?>
                    <?= Yii::$app->controls->reset(); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$script = "
    var dualbox = $('#importform-fields');
    $('#importform-module_name').val('');
    $('#importform-module_name').on('change',function(){           
        var mod = $(this).val();     
        $.ajax({
                type: 'post',
                url: '" . $url . "',
                data: 'type='+mod+'&flag='+'{$flag}',
                success: function(data) {
                    var res=$.parseJSON(data);
                    if (res.fields !== '')
                    {
                        var fields=res.fields;
                        $('#imp_fields').empty();
                        $('#importform-fields').empty();
                        fields=fields.replace(', ',',');
                        var ary=fields.split(',');
                        $.each(ary, function(index, value) {
                            value=$.trim(value);
                            dualbox.append($('<option>').text(value).val(value));
                        });
                        dualbox.bootstrapDualListbox('refresh', true);
                    }
                    else
                    {
                        $('#imp_fields').empty();
                    }
                    $('#default_flds').html('<b>Default Fields:</b><br/>'+res.def_fields);
                    res.def_fields=res.def_fields.replace(', ',',');
                    $('#importform-required').val(res.def_fields);
                },
                error:function(data){
                            //alert('Your data has not been submitted..Please try again');
                        }
            });
                            
    }); 
";
$this->registerJs($script, View::POS_END, 'village-code');
?>