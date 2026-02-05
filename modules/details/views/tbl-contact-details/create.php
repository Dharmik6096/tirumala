<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

//Url::remember();
$form_validation_type = !empty($form_validation_type) ? $form_validation_type : 'default';
$mail_info = !empty($mail_info) ? $mail_info : (in_array($module, ['mccPlant', 'plant']) ? TRUE : FALSE);
$show_optional_fields = in_array($module, ['mccPlant', 'bmc', 'society', 'routeMapping']);
$url = Url::to(['/details/tbl-contact-details/create', 'module' => $module, 'id' => $id, 'form_validation_type' => $form_validation_type]);
$this->title = Yii::$app->label->title('create', 'Contact Detail');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">

        <?php
        $form = ActiveForm::begin([
                    'action' => $url,
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
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
                'mail_info' => $mail_info,
                'show_optional_fields' => $show_optional_fields,
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
                    'show_optional_fields' => $show_optional_fields,
                ])
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$script = "
     $('.edit-record').on('click',function(event){       
        var id= $(this).attr('data-val');
        editContactDetail(id);
    });
    
    function editContactDetail(detail_code){
            if(detail_code != ''){         
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/details/tbl-contact-details/update-contact']) . "',
                    data: {'detail_code' : detail_code},
                    beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#tblcontactdetails-mobile_no').prop('readonly', true);
                        $.each(data.modelData, function(index, value) {
                            $('#tblcontactdetails-' + index).val(value).trigger('change');
                        });
                         $('#loadercontent').hide();
                         $('#pageloader').hide();
                         $(window).scrollTop(0);

                    },
                });
            }
    };
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>