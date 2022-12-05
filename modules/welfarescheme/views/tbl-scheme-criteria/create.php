<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$url = Url::to(['/welfarescheme/tbl-scheme-criteria/create', 'id' => $id]);
$this->title = Yii::$app->label->title('create', 'Scheme Criteria');
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">

        <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'schemeCriteria',
                    ],
                    'action' => $url,
                    'validateOnBlur' => FALSE,
                    'validateOnChange' => FALSE,
                    'enableAjaxValidation' => false,
                    'enableClientValidation' => false,
                    'validateOnSubmit' => FALSE,
        ]);
        ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="row">
            <?=
            $this->render('_form', [
                'model' => $model,
                'type' => 'create'
            ])
            ?>
            <div class="col-sm-4 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?php
                    $requestUrl = \Yii::$app->request->getHostInfo() . Yii::$app->request->url;
                    AjaxSubmitButton::begin([
                        'label' => Yii::t('app', 'Save'),
                        'ajaxOptions' => [
                            'type' => 'POST',
                            'url' => $url,
                            'beforeSend' => new JsExpression("function(data){
                                            $('#loadercontent').show();
                                            $('#pageloader').show();
                                        }"),
                            'success' => new JsExpression('function(data){
                                                        var data=$.parseJSON(data);
                                                        $(".help-block").text("");
                                                        $(".form-group").removeClass("has-error");
                                                        $(".error-summary").hide();
                                                        $(".error-summary li").remove();
                                                        if (data.status == "success"){ 
//                                                            location.reload();
                                                            window.location = "' . $requestUrl . '";
                                                        } else if (data.status == "error") {
                                                            $("#loadercontent").hide();
                                                            $("#pageloader").hide();
                                                            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
                                                        } else {
                                                            $("#loadercontent").hide();
                                                            $("#pageloader").hide();
                                                            $.each(data, function(key, val) {
                                                                $(".error-summary ul").append("<li>"+val+"</li>");
                                                            });
                                                            $(".error-summary").show();
                                                        }
                                                 }'),
                        ],
                        'options' => ['class' => 'btn btn-default btn-save-txn saveSchemeCriteria',
                            'type' => 'submit'],
                    ]);
                    AjaxSubmitButton::end();
                    ?>
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


