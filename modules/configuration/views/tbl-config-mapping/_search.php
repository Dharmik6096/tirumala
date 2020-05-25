<?php

use yii\widgets\ActiveForm;
use yii\web\View;
?>

<div class="tbl-milk-collection-search">

    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'field-class' => 'form-group col-sm-2 padding-right-5',
                    'id' => 'config-mapping-search',
                ],
                'method' => 'get',
    ]);
    ?>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->configFor($model, $form, 'config_for', $model->getAttributeLabel('config_for'), false, ['VLC', 'PORTAL']); ?>  
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->processName($model, $form, 'process_name', $model->getAttributeLabel('process_name'), false); ?>  
    </div>  
   
    <?php ActiveForm::end(); ?>

</div>
<?php
$script = "
    $('#tblconfigsearch-config_for').change(function() {
        submitForm();
    });
    $('#tblconfigsearch-process_name').change(function() {
        submitForm();
    });

    function submitForm(){
        var config = $('#tblconfigsearch-config_for').val();
        var process = $('#tblconfigsearch-process_name').val();
        if(config !='' && process !='') {
             $('form#config-mapping-search').submit();
             setTimeout(function() {
             $('.showHideData').show();
              },2000);
        }  else {
            $('.showHideData').hide();
        }
    } 

   
    
     ";
$this->registerJs($script, View::POS_END, 'config-mapping-search');
