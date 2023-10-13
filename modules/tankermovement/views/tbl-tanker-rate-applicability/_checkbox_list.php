<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use app\components\GeneralFunctions;


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php
echo $form->field($model, $field_name . '[]')->checkboxList(
        $list, [
    'id' => $field_name . '-list',
    'class' => 'row checkboxFilterList',
    'item' =>
    function ($index, $label, $name, $checked, $value) use ($selected) {
        $checked = in_array($value, $selected);
        $disabled = '';
        if (!$checked) {
            return "<div class='col-sm-2 dcs-checklist checklist'><div class='checkbox'>" . Html::checkbox($name, $checked, [
                        'value' => $value,
                        'label' => '<label for="' . $value . '">' . $label . '</label>',
                        'labelOptions' => [
                            'class' => 'route-text' . $disabled,
                        ],
                        'class' => 'route-checkbox',
                        'id' => $value,
                    ]) . "</div></div>";
        }
    }, /* ,'template'=>'<div class="item">{input}{label}</div>' */])->label(false);

?>