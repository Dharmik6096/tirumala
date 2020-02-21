<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use app\components\GeneralFunctions;

$selectedDataCode = !empty($selectedData) ? $selectedData : [];
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!empty($model)) {
    echo $form->field($model, $field_name . '[]')->checkboxList(
            $list, [
        'id' => $field_name . '-list',
        'class' => 'row',
        'item' =>
        function ($index, $label, $name, $checked, $value) use ($selected, $selectedDataCode) {
            $checked = in_array($value, $selected);
            $disabled = '';
            if (!$checked) {
                $checkData = in_array($value, $selectedDataCode);
                return "<div class='col-sm-4 dcs-checklist checklist' id='nd-" . $value . "'><div class='checkbox'>" . Html::checkbox($name, $checkData, [
                            'value' => $value,
                            'label' => '<label for="' . $value . '">' . $label . '</label>',
                            'labelOptions' => [
                                'class' => 'route-text' . $disabled,
                            ],
                            'class' => 'route-checkbox',
                            'id' => $value
                        ]) . "</div></div>";
            }
        }, /* ,'template'=>'<div class="item">{input}{label}</div>' */])->label(false);
} else {
    echo Html::checkboxList($field_name . '[]', null, $list, [
        'id' => $field_name . '-list',
        'class' => 'row flt',
        'style' => 'display:none',
        'item' =>
        function ($index, $label, $name, $checked, $value) use ($selected, $field_name) {
            $checked = in_array($value, $selected);
            $disabled = '';
            if (!$checked) {
                return "<div class='col-sm-12 dcs-checklist checklist' id='nd-" . $value . "'><div class='checkbox'>" . Html::checkbox($name, $checked, [
                            'value' => $value,
                            'label' => '<label for="' . $value . '">' . $label . '</label>',
                            'labelOptions' => [
                                'class' => ' route-text' . $disabled,
                            ],
                            'class' => 'flt-checkbox route-checkbox',
                            'data-flt' => $field_name,
                            'id' => $value,
                        ]) . "</div></div>";
            }
        }, /* ,'template'=>'<div class="item">{input}{label}</div>' */]);
}
?>
