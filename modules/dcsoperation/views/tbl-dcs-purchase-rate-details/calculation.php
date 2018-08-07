<?php

use yii\helpers\Html;
?>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-main table-language table-rate">
        <?php if ($purchaseModel->rate_type == 0) { ?>
            <thead>
                <tr>
                    <th>FAT</th>
                    <th>RTPL</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modelAtteributes as $key => $attr) { ?>
                    <tr>
                        <td><?= $attr->fat; ?></td>
                        <td><?= $form->field($attr, '[' . $key . ']rtpl')->textInput(['maxlength' => true, 'class' => 'form-control number-validate control-inline'])->label(false) ?></td>
                        <?= Html::activeHiddenInput($attr, '[' . $key . ']fat') ?>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } else { ?>
            <thead>
                <tr>
                    <th>FAT/SNF</th>
                    <?php for ($i = $_POST['TblDcsPurchaseRateAuto']['snf']; $i <= $_POST['TblDcsPurchaseRateAuto']['snf_to']; $i+=0.10) { ?>
                        <th><?= $i ?></th>
                        <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modelAtteributes as $key => $attr) { ?>
                    <tr>
                        <td><?= $attr->fat; ?></td>

                        <?php
                        $snf = 0;
                        for ($i = $_POST['TblDcsPurchaseRateAuto']['snf']; $i <= $_POST['TblDcsPurchaseRateAuto']['snf_to']; $i+=0.10) {
                            ?>

                            <td>
                                <?php echo $form->field($attr, '[' . $key . '][' . $snf . ']rtpl')->textInput(['maxlength' => true, 'class' => 'form-control number-validate control-inline'])->label(false) ?>
                                <?= Html::activeHiddenInput($attr, '[' . $key . '][' . $snf . ']fat') ?>
                                <?= Html::activeHiddenInput($attr, '[' . $key . '][' . $snf . ']snf', ['value' => $i]) ?>
                            </td>

                            <?php
                            $snf++;
                        }
                        ?>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } ?>    
    </table>
</div>