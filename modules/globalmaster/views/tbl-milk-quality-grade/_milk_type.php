<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$disable = (isset($type))?' disabled':'';
?>
<?php Pjax::begin(['id' => 'milk-quality-list',  'linkSelector'=>false]);?>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-main table-language">
        <thead>
            <tr>
                <th>Milk Type</th>
                <th>Min Value</th>
                <th>Max Value</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($milkType as $key=>$type){
                    //$values = $model->getRecord($type->code,$model->animal_type_code,$model->union_code,$model->grade_name);
                ?>
            <tr>
                <td><?= $type->param ?></td>
                <td><?= $form->field($modelMilk[$key], '[' . $key . ']min_rang')->textInput(['maxlength' => true,/*'value'=>$values['min_rang'],*/ 'class' => 'form-control number-validate control-inline'.$disable])->label(false) ?></td>
                <td><?= $form->field($modelMilk[$key], '[' . $key . ']max_rang')->textInput(['maxlength' => true,/*'value'=>$values['max_rang'],*/ 'class' => 'form-control number-validate control-inline'.$disable])->label(false) ?></td>
                <?= Html::activeHiddenInput($modelMilk[$key], '[' . $key . ']milk_type_code',['value'=>$type->id]) ?>
                <?= Html::activeHiddenInput($modelMilk[$key], '[' . $key . ']animal_type_code',['value'=>$modelMilk[$key]->animal_type_code]) ?>
                <?= Html::activeHiddenInput($modelMilk[$key], '[' . $key . ']union_code',['value'=>$modelMilk[$key]->union_code]) ?>
                <?= Html::activeHiddenInput($modelMilk[$key], '[' . $key . ']grade_code',['value'=>$modelMilk[$key]->grade_code/*$values['grade_code']*/]) ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php Pjax::end(); ?>