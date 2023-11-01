<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$url = Url::to(['/organisation/tbl-dcs-bmc/bmc-chiller-info', 'module' => $module, 'id' => $id]);
$this->title = Yii::$app->label->title('create', 'Chiller Info');
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
<?php
$script = "
     $('.edit-record').on('click',function(event){       
        var id= $(this).attr('data-val');
        editChillerInfo(id);
    });
    
    function editChillerInfo(chiller_info_code){
            if(chiller_info_code != ''){         
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/organisation/tbl-dcs-bmc/update-chiller-info']) . "',
                    data: {'chiller_info_code' : chiller_info_code},
                    beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                    },
                    success: function(data) {
                        $.each(data.modelData, function(index, value) {
                            $('#tblbmcchillerinfo-'+index).val(value);
                        });

                        $('#tblbmcchillerinfo-rate_type').val(data.modelData.rate_type).trigger('change');
                        $('#tblbmcchillerinfo-is_active').val(data.modelData.is_active).trigger('change');

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