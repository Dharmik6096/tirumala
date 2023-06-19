<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php
$index = 1;
foreach ($config_list as $c) {
    ?>
    <?= Html::activeHiddenInput($config, '[' . $index . ']config_code', ['value' => $c->config_code]); ?>
    <div class="dynamic-controls">
        <?= $c->prepareControl($form, $config, $index); ?>
    </div>

    <?php
    $index++;
}
?>

<?= ($auto_reject == '1') ? '<div class="clearfix"></div><br/> <div class="col-sm-5"> <p style="color:red">' . Yii::t('app', 'Note :: Milk Will be auto rejected if any positive adulteration found.') . '</p></div>' : ''
?>