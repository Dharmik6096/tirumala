<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\tblmccpaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-report_request-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>  

    <div class="col-sm-2">
        <?= Yii::$app->controls->date($searchModel, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
        <div class="col-sm-2">
        <?= Yii::$app->controls->date($searchModel, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('user_code', $searchModel, $form, '', 'User Name', false, 'user_code'); ?>
    </div>
    <div class="form-group padding_top_20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

