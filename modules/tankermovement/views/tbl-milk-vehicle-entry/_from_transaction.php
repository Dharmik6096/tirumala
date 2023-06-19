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
foreach ($config_list as $c) {
    $index = $c->config_code;
    ?>
    <?= Html::activeHiddenInput($config, '[' . $index . ']config_code', ['value' => $c->config_code]); ?>
    <div class="dynamic-controls">
        <?= $c->prepareControl($form, $config, $index); ?>
    </div>

    <?php
}
?>
