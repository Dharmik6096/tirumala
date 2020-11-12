<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$url = Url::to(['/details/tbl-bank-details/create', 'module' => $module, 'id' => $id]);
$this->title = Yii::$app->label->title('create', 'Bank Detail');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">

        <?php
        $form = ActiveForm::begin([
                    'action' => $url,
                    'validateOnBlur' => FALSE,
                    
                    'validateOnChange' => FALSE,
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>

        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <?=
            $this->render('_form', [
                'model' => $model,
                'form' => $form,
                'dist' => $dist,
                'dist_field' => $dist_field
            ])
            ?>
            <div class="col-sm-4 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
                    <?= Yii::$app->controls->reset(); ?>
                    <?= Yii::$app->controls->cancel($model); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="row">
            <div class="form-grid">
                <?=
                $this->render('_form_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ])
                ?>
            </div>
        </div>
    </div>
</div>