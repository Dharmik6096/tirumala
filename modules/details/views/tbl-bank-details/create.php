<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$nameWarning = 0;
$nameWarning = !empty($_POST['warning']) ? $_POST['warning'] : 0;
$url = Url::to(['/details/tbl-bank-details/create', 'module' => $module, 'id' => $id]);
$this->title = Yii::$app->label->title('create', 'Bank Detail');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">

        <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'bankDetails',
                    ],
                    'action' => $url,
                    'validateOnBlur' => FALSE,
                    'validateOnChange' => FALSE,
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>

        <?php echo $form->errorSummary($model); ?>
        <?= Html::hiddenInput('warning', $nameWarning, ['id' => 'warning']); ?>
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
                                                        } else if (data.status == "askConfirm") {
                                                            $("#loadercontent").hide();
                                                            $("#pageloader").hide();
                                                            bootbox.confirm({
                                                                message: "<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-question-circle\'></i></div><span>"+data.msg+"</span></div></div>",
                                                                buttons: {
                                                                    confirm: {
                                                                        label: "' . Yii::t('app', 'Yes') . '",
                                                                        className: "btn-primary"
                                                                    },
                                                                    cancel: {
                                                                        label: "' . Yii::t('app', 'No') . '",
                                                                        className: "btn-danger"
                                                                    }
                                                                },
                                                                callback: function (result) {
                                                                    if(result){
                                                                        $("#loadercontent").show();
                                                                        $("#pageloader").show();
                                                                        $("#warning").val(1);
                                                                        $(".saveBankDetails").trigger("click");
//                                                                        $("form#bankDetails").submit();
                                                                    } else {
                                                                        $("#warning").val(0);
                                                                    }
                                                                }
                                                            });
//                                                            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
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
                        'options' => ['class' => 'btn-login btn btn-default btn-save-txn saveBankDetails',
                            'type' => 'submit'],
                    ]);
                    AjaxSubmitButton::end();
                    ?>
                    <?php // Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
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