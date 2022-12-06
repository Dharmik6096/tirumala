<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\jui\DatePicker;
use yii\helpers\Url;

$button = Yii::$app->label->button('create');
$this->title = Yii::t('app', 'Scheme Document Mapping');
$defaultToggle = true;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <div class="panel-body">
        <div class="padding-0">
            <?php
            echo $this->render('_form', [
                'type' => 'create',
                'model' => $model]);
            ?>
            <?php
            $form = ActiveForm::begin(['options' => [
                            'field-class' => 'form-group col-sm-3'
                        ], 'validateOnBlur' => FALSE,
                        'validateOnChange' => FALSE,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
                        'fieldConfig' => [
            ]]);
            ?>
            <?php echo $form->errorSummary($model); ?>
            <div class="panel-body set_checkbox padding_top_0 tbl_border">

                <div class="clearfix"></div>
                <div class="panel-subheading hide_help_block">
                    <div class="row">
                        <div class="table-responsive">
                            <?php
                            if (!empty($dataProvider->getModels())) {
                                $data = $dataProvider->getModels();
                                $defaultToggle = false;
                                $count = count($data);
                                $disp_table = $count / 2;
                                $first_table = ceil($disp_table);
//                            $first_table = round($disp_table, 0, PHP_ROUND_HALF_DOWN);
                                $second_table = $first_table * 2;
                                ?>
                                <div class="col-sm-6">
                                    <table class="table table-bordered table-striped table-main table-language table-rate">
                                        <thead>
                                            <tr>
                                                <th width='5%' height='35' class='center-align center_text'><?= Html::checkbox('allowCashCheckAll', false, ['id' => 'allowCashCheckAll', 'class' => 'checkbox', 'label' => '']) ?></th>
                                                <th width='80%' height='35'><?php echo $model->getAttributeLabel('doc_name') ?></th>
                                                <th width='5%' height='35'><?php echo $model->getAttributeLabel('is_mandate') ?></th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for ($i = 0; $i < $first_table; $i++) { ?>
                                                <tr>
                                                    <td class='center-align center_text'>
                                                        <?php
                                                        $val = $data[$i]['doc_id'];
                                                        $selected = in_array($val, $selectedArray);
                                                        echo Html::checkbox('configCodes[]', $selected, ['class' => 'allow-cash-checkbox checkbox', 'label' => '', 'value' => $val]);
                                                        ?>
                                                    </td>
                                                    <td><?= $data[$i]->doc_name ?></td>
                                                    <td><?= Html::checkbox('configCodes[]', $selected, ['class' => 'allow-cash-checkbox checkbox', 'label' => '', 'value' => $val]); ?></td>
                                                </tr>

                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <table class="table table-bordered table-striped table-main table-language table-rate">
                                        <thead>
                                            <tr>
                                                <th width='5%' height='35'></th>
                                                <th width='80%' height='35'><?php echo $model->getAttributeLabel('doc_name') ?></th>
                                                <th width='5%' height='35'><?php echo $model->getAttributeLabel('is_mandate') ?></th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            for ($i = $first_table; $i < $second_table && $i < $count; $i++) {
                                                ?>
                                                <tr>
                                                    <td class='center-align center_text'>
                                                        <?php
                                                        $val = $data[$i]['doc_id'];
                                                        $selected = in_array($val, $selectedArray);
                                                        echo Html::checkbox('configCodes[]', $selected, ['class' => 'allow-cash-checkbox checkbox', 'label' => '', 'value' => $val]);
                                                        ?>
                                                    </td>
                                                    <td><?= $data[$i]->doc_name ?></td>
                                                    <td><?= Html::checkbox('configCodes[]', $selected, ['class' => 'allow-cash-checkbox checkbox', 'label' => '', 'value' => $val]); ?></td>
                                                </tr>

                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <?php
                            } else {
                                echo "<p class='text-center'>" . Yii::t('app', 'Data Not Available') . "</p>";
                                $defaultToggle = true;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <?php if (!empty($dataProvider->getModels())) { ?>
                    <?= Yii::$app->controls->save($button, $model); ?>
                    <?= Yii::$app->controls->reset(); ?>
                <?php } ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php
$script = "
    hideDiv();
    $('#allowCashCheckAll').click(function () {
        var check =this.checked;
        $('.allow-cash-checkbox').each(function () {
            this.checked = check;
        });
    });
    $('.allow-cash-checkbox').each(function () {
        $('#allowCashCheckAll').prop('checked', true);
        if(this.checked == false){
            $('#allowCashCheckAll').prop('checked', false);
        }
    });
  

";

$this->registerJs($script, View::POS_END, 'force-rate-download');
