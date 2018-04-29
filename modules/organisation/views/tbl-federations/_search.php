<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblFederationsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$form = ActiveForm::begin([
            'action' => isset($actions) ? $actions : ['create'],
            'method' => 'get',
        ]);
?>
<div class="col-sm-3">
    <?= $form->field($model, 'federation_code', ['options' => ['class' => 'form-group col-sm-3 padding-right-0']])->label(false) ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
<?php ActiveForm::end(); ?>