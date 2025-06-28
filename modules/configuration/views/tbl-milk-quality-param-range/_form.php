<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div id='quality_details'>
    <?php $form = ActiveForm::begin(['id' => 'add-quality-detail']); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="row hr10">
        <div class="col-sm-12 mt20">
            <div class="panel panel-default">
                <table  class="table table-bordered table-striped table-main table-language br_grey bl_grey">
                    <thead>
                        <tr>
                            <th>Milk Type</th>
                            <th>Min FAT</th>
                            <th>Max FAT</th>
                            <th>Min SNF</th>
                            <th>Max SNF</th>
                            <th>Min CLR</th>
                            <th>Max CLR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($animalDetail as $animal) {
                            $index = $animal->animal_type_code;
                            $animal_type_name = $animal->animal_type_name;
                            $existingRecord = null;
                            foreach ($existingRecords as $record) {
                                if ($record['animal_type_code'] == $index) {
                                    $existingRecord = $record;
                                    break;
                                }
                            }
                            $min_fat_value = isset($existingRecord['min_fat']) ? round($existingRecord['min_fat'], 2) : '';
                            $max_fat_value = isset($existingRecord['max_fat']) ? round($existingRecord['max_fat'], 2) : '';
                            $min_snf_value = isset($existingRecord['min_snf']) ? round($existingRecord['min_snf'], 2) : '';
                            $max_snf_value = isset($existingRecord['max_snf']) ? round($existingRecord['max_snf'], 2) : '';
                            $min_clr_value = isset($existingRecord['min_clr']) ? round($existingRecord['min_clr'], 2) : '';
                            $max_clr_value = isset($existingRecord['max_clr']) ? round($existingRecord['max_clr'], 2) : '';
                            ?>
                            <tr>
                                <td class="hide_help_block"><?= $animal_type_name ?><?= Html::activeHiddenInput($model, '[' . $index . ']animal_type_code', ['value' => $index]) ?></td>
                                <td class="hide_help_block"><?= $form->field($model, '[' . $index . ']min_fat')->textInput(['class' => 'form-control number-validate', 'value' => $min_fat_value])->label(false) ?></td>
                                <td class="hide_help_block"><?= $form->field($model, '[' . $index . ']max_fat')->textInput(['class' => 'form-control number-validate', 'value' => $max_fat_value])->label(false) ?></td>
                                <td class="hide_help_block"><?= $form->field($model, '[' . $index . ']min_snf')->textInput(['class' => 'form-control number-validate', 'value' => $min_snf_value])->label(false) ?></td>
                                <td class="hide_help_block"><?= $form->field($model, '[' . $index . ']max_snf')->textInput(['class' => 'form-control number-validate', 'value' => $max_snf_value])->label(false) ?></td>
                                <td class="hide_help_block"><?= $form->field($model, '[' . $index . ']min_clr')->textInput(['class' => 'form-control number-validate', 'value' => $min_clr_value])->label(false) ?></td>
                                <td class="hide_help_block"><?= $form->field($model, '[' . $index . ']max_clr')->textInput(['class' => 'form-control number-validate', 'value' => $max_clr_value])->label(false) ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save('SAVE', $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
</div>
<?php ActiveForm::end(); ?>