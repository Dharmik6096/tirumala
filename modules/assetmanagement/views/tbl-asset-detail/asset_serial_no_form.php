
<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
?>
<div class="modal-body">
    <?php
    $form = ActiveForm::begin(['options' => [
                    'id' => 'sr_no_form',
                    'field-class' => 'form-group col-sm-6',
                    'class' => 'mt15'
                ],
                'validateOnBlur' => FALSE,
                
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
    ]);
    ?>
    <div class="checkbox ml15 select-all-div">
        <input type = "checkbox" id = "select_all"><label for = "select_all">Select All</label>
    </div>
    <div class="">
        <?php
        echo Html::hiddenInput('total_sr_no', $sr_count, ['id' => 'serial_no_count']);
        echo $form->field($model, 'serial_number[]')->checkboxList(
                $serialNoRecords, [
            'id' => 'serial_no_list',
            'item' =>
            function ($index, $label, $name, $checked, $value) use ($selectedRecords) {
                $checked = in_array((string) $value, $selectedRecords, true);
                return "<div class='col-sm-4 checklist dcs-checklist'><div class='checkbox'>" . Html::checkbox($name, $checked, [
                            'value' => $value,
                            'label' => '<label for = "' . $value . '">' . $label . '</label>',
                            'labelOptions' => [
                                'class' => 'select2-container',
                            ],
                            'class' => 'serial_no_checkbox',
                            'id' => $value
                        ]) . "</div></div>";
            },])->label(false);
                ?>
            </div>
            <?php
            ActiveForm::end();
            ?>
        </div>

        <div class="modal-footer">
            <?=
            Html::a(Yii::t('app', 'Save'), 'javascript:void(0)', ['class' => 'btn btn-primary add_serial_no'])
            ?>
            <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
        </div> 