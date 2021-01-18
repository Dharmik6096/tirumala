<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', 'TS Loss And Shortage');
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'transit-loss-shortage',
    ]);
    ?>
    <div class="">
        <?php echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']); ?>
        <?php
        if (!empty($output)) {

            $attr = [];
            foreach ($output[0] as $att => $value) {

                $checkAttr = explode('##', $att);
                $attr_arr = [];
                $format = 'raw';
                if (in_array($att, ['Quantity', 'FAT', 'CLR', 'SNF'])) {
                    $format = ['decimal', 2];
                }
//                    $attr_arr['attribute'] = $att;
                if (empty($checkAttr[1]) || $checkAttr[0] != $checkAttr[1]) {
                    $attr_arr = [];
                    if (!empty($data['to_decrypt']) && in_array($checkAttr[0], $data['to_decrypt'])) {
                        $attr_arr['value'] = function($model) use ($att) {
                            return !empty($model[$att]) ? (Yii::$app->general->decryptData($model[$att]) !== FALSE ? Yii::$app->general->decryptData($model[$att]) : $model[$att]) : (isset($model[$att]) && $model[$att] == 0 && $model[$att] != '' ? 0 : '');
                        };
                    }
                    if ($att == 'ts_loss_responsibility') {
                        $attr_arr['value'] = function ($model, $key, $index) use ($form, $att, $value, $modelData) {
                            return '<span class=\'loss_responsibility\'>' . Yii::$app->dropdown->dropdownStatic('loss_responsibility', $modelData[$index], $form, 'form-group', FALSE, false, '[' . $index . ']ts_loss_responsibility', false, false, TRUE, TRUE) . '</span>';
                        };
                    }
                    if ($att == 'qty_diff_type') {
                        $attr_arr['value'] = function ($model, $key, $index) use ($form, $att, $value, $modelData) {
                            return '<span class=\'qty_diff_type\'>' . Yii::$app->dropdown->dropdownStatic('penalty_type', $modelData[$index], $form, 'form-group', FALSE, false, '[' . $index . ']qty_diff_type', false, false, TRUE, TRUE) . '</span>';
                        };
                    }
                    if ($att == 'qty_diff_responsibility') {
                        $attr_arr['value'] = function ($model, $key, $index) use ($form, $att, $value, $modelData) {
                            return '<span class=\'qty_diff_responsibility\'>' . Yii::$app->dropdown->dropdownStatic('loss_responsibility', $modelData[$index], $form, 'form-group', FALSE, false, '[' . $index . ']qty_diff_responsibility', false, false, TRUE, TRUE) . '</span>';
                        };
                    }
                    if ($att == 'shortage_recovery') {
                        $attr_arr['value'] = function ($model, $key, $index) use ($form, $att, $value, $modelData) {
                            return $form->field($modelData[$index], '[' . $index . ']shortage_recovery')->textInput(['class' => 'form-control col-sm-3'])->label(FALSE);
                        };
                    }
                    if ($att == 'total_recovery_incharge') {
                        $attr_arr['value'] = function ($model, $key, $index) use ($form, $att, $value, $modelData) {
                            return $form->field($modelData[$index], '[' . $index . ']total_recovery_incharge')->textInput(['class' => 'form-control col-sm-3'])->label(FALSE);
                        };
                    }
                    if ($att == 'total_recovery_incharge') {
                        $attr_arr['value'] = function ($model, $key, $index) use ($form, $att, $value, $modelData) {
                            return $form->field($modelData[$index], '[' . $index . ']total_recovery_incharge')->textInput(['class' => 'form-control col-sm-3'])->label(FALSE);
                        };
                    }
                    if ($att == 'total_recovery_transporter') {
                        $attr_arr['value'] = function ($model, $key, $index) use ($form, $att, $value, $modelData) {
                            return $form->field($modelData[$index], '[' . $index . ']total_recovery_transporter')->textInput(['class' => 'form-control col-sm-3'])->label(FALSE);
                        };
                    }
                    $str = ucwords(str_replace('_', ' ', $att));
                    $attr_arr['attribute'] = $att;
                    $attr_arr['label'] = Yii::t('app', $str);
                    $attr_arr['format'] = $format;
                    $attr_arr['filter'] = false;
                    $attr[] = $attr_arr;
//                    $attr[] = ['attribute' => $att, 'label' => Yii::t('app', $str), 'format' => $format, 'filter' => false];
                }
            }

            $grid_option = [
                'id' => 'transit-loss-shortage',
                'attributes' => $attr,
                'active_column' => false,
                'showPageSummary' => false,
                'default_sorting' => FALSE,
            ];

            Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['index'], true);
        }
        ?>
        <div class="panel-footer">
            <?php
            if (!empty($output)) {
                echo Html::button(Yii::t('app', 'LOCK'), ['class' => 'btn btn-primary submit', 'id' => 'lock', 'value' => 'lock', 'name' => 'lock']);
                echo Html::button(Yii::t('app', 'UNKLOCK'), ['class' => 'btn btn-primary submit', 'id' => 'unlock', 'value' => 'unlock', 'name' => 'unlock']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'transit-loss-shortage'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div id="AppInformation"></div>

<?php
$script = '
    $(".kv-panel-before").hide();
    $(".submit").click(function() {
      var id= $(this).attr("value");
      $(".set_operation").val(id);
        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Record.</span></div></div>");
                return false;
            } else {
            $("#bulk-verification").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'transit-loss-shortage');
