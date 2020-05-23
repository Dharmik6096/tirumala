<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
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

<?= ($auto_reject == '1') ? '<div class="clearfix"></div> <div class="col-sm-5"> <p style="color:red">' . Yii::t('app', 'Note :: Milk Will be auto rejected if any positive adultartion found.') . '</p></div>' : ''
?>