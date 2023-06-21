<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kato\DropZone;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;

$url = Url::to(['get-fields']);
$readonly = false;
$import = Yii::$app->request->get('ImportForm');
$module = $import['module_name'];
$required = explode(',', $import['required']);
$selected = !empty($selected)?array_merge($selected, $required):$required;
$action=  Url::to(['import']);
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?php echo Yii::t('app', 'Import Data'); ?>
    </div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'import-gen',
                    'method' => 'GET',
                    'action' => $action,
                    'validateOnBlur' => false,
        ]);
        ?>
        <?= $form->errorSummary($model); ?>
        <div class="row">
            <?= Html::hiddenInput('flag', $flag); ?>
            <?= Html::activeHiddenInput($model, 'module_name', ['value' => $module]) ?>
            <?= Html::activeHiddenInput($model, 'fields') ?>
            <div class="col-sm-6">
                <?=
                $form->field($model, 'required')->checkboxList($selected, ['class' => 'sort', 'tag' => 'ul', 'item' => function ($index, $label, $name, $checked, $value) use($required){
                        $checked= in_array($label, $required)?1:0;
                        $clk=in_array($label, $required)?'this.checked=!this.checked;':'';
                        return '<li class="sort-li" id="' . $label . '"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'
                                . Html::checkbox($name, $checked, [
                                    'value' => $label,
                                    'onClick'=>$clk,
                                    'label' => '<label for="' . $label . '">' . $label . '</label>',
                                    'labelOptions' => [
                                    // you can set label options here                                                ],
                                    ],
                                ]) . '</li>';
                    }]);
                        ?>
                    </div>


        <?= Html::activeHiddenInput($model, 'operation'); ?>

                    <div class="clearfix"></div>
                    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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
    $('#importform-required').sortable({
    stop: function( event, ui ) {
        storeSorted();
    }
   } );
   storeSorted();
    
    function selectAll(select){
        $('option', select).prop('selected', true);
    }    
    $('body').on('click','.next',function(e) {
        e.preventDefault();
        selectAll($('#importform-required'));
        return true;
    });
    
    function storeSorted()
    {
        var sortedIDs = $('#importform-required').sortable('toArray');
        $('#importform-fields').val(sortedIDs);
    }
    
";
        $this->registerJs($script, View::POS_END, 'village-code');
        ?>