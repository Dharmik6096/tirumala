<?php

use yii\widgets\ActiveForm;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use yii\helpers\Html;

$form = ActiveForm::begin(['options' => [
                'id' => 'dynamicreport-form',
                'field-class' => 'form-group col-sm-6'
            ],
            'method' => 'get',
            'action' => Url::to(['index']),
            'validateOnBlur' => FALSE,
//            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>   

<div class="row margin_0">

    <div class="modal-body">
        <div class="col-sm-12">
            <?= Yii::$app->dropdown->dropdown('report_code', $model, $form, '', 'Report Name'); ?>
        </div>
        <?php
        $param = isset($data['controls']) ? $data['controls'] : [];
        foreach ($param as $control) {
            $label = Yii::t('app', $control->control_label);
            $cname = $control->control_name;
            $spname = $control->control_sp;
            $paramname = $control->control_param;
            if ($control->control_type == 'date') {
                ?>
                <div class="col-sm-3">
                    <?php
                    echo Yii::$app->controls->date($model, $form, $control->control_name, 'form-group col-sm-3 padding-left-5 padding-right-5', false);
                    ?>
                </div>    
                <?php
            } if ($control->control_type == 'dep_drop') {
                echo Html::hiddenInput('sp_' . $cname . '_name', $spname, ['id' => $cname . '_sp_name']);
                echo Html::hiddenInput('sp_' . $cname . 'param', $paramname, ['id' => $cname . '-sp_param']);
                $depends = $cname . '_sp_name,' . $cname . '-sp_param';
                $paramname = !empty($paramname) ? 'dynamicform-' . str_replace(',', ',dynamicform-', $paramname) : '';
                $depends = !empty($paramname) ? $depends . ',' . $paramname : $depends;
                ?>
                <div class="col-sm-3">
                    <?php
                    echo Yii::$app->dropdown->sp_dep_dropdown($model, $form, $depends, $cname, $label, $control->control_param);
                    ?>
                </div>    
                <?php
            } if ($control->control_type == 'dropdown') {
                ?>
                <div class="col-sm-3 shift">
                    <?php
                    echo Yii::$app->dropdown->sp_dropdown($cname, $model, $form, 'col-sm-6 form-group', $label, $spname, $paramname);
                    ?>
                </div>   
                <?php
            } if ($control->control_type == 'staticdd') {
                ?>
                <div class="col-sm-3 shift">
                    <?php
                    echo Yii::$app->dropdown->sp_dropdown($cname, $model, $form, 'col-sm-6 form-group', $label, $spname, $paramname);
                    ?>
                </div>   
                <?php
            }if ($control->control_type == 'text') {
                ?>
                <div class="col-sm-3">
                    <?php
                    echo $form->field($model, $cname)->textInput(['maxlength' => true])
                    ?>
                </div>   
                <?php
            }if ($control->control_type == 'hidden') {
                ?>
                <div class="col-sm-3 shift">
                    <?php
                    echo Html::hiddenInput('sp_' . $cname . '_name', $paramname, ['id' => $cname . '_sp_name']);
                    ?>
                </div>   
                <?php
            }
        }
        ?>
        <?php
        echo $form->field($model, 'output_type', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList(['DOWNLOAD' => 'DOWNLOAD', 'VIEW' => 'VIEW']);
        ?>
        <!--<div class="col-sm-3 mt25">-->
        <?php
        if ($param) {
//                echo GhostHtml::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'submit']);
        }
        ?>
        <!--</div>-->
    </div>

    <div class="modal-footer mt10 col-sm-12">
        <?php
        if ($param) {
            echo GhostHtml::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'html', 'value' => 'html', 'id' => 'html']);
        }
        ?>
        <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
    </div>
</div>
<?php ActiveForm::end(); ?>