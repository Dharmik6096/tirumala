<?php

use yii\widgets\ActiveForm;
use yii\web\View;
?>

<div class="tbl-milk-collection-search">

    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'field-class' => 'form-group col-sm-2 padding-right-5',
                    'id' => 'document-mapping-search',
                ],
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('master_types', $model, $form, 'form-group', $model->getAttributeLabel('master_type'), false, 'master_type', false); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = "
    updateGrid();
    $('#tbldocumentmapping-master_type').change(function() {
        submitForm();
    });

    function submitForm(){
        var master_type = $('#tbldocumentmapping-master_type').val();
        if(master_type =='member') {
             $('form#document-mapping-search').submit();
             setTimeout(function() {
                 $('.showHideData').show();
             },2000);
        }
        else if(master_type != ''){
         $('form#document-mapping-search').submit();
             setTimeout(function() {
                 $('.showHideData').show();
             },2000);
        }
        else {
            $('.showHideData').hide();
        }       
    } 

     function updateGrid() {
        var master_type = $('#tbldocumentmapping-master_type').val();
        if(master_type !='') {
            $('.showHideData').show();
        } else {
            $('.showHideData').hide();
        }
    }
    
     ";
$this->registerJs($script, View::POS_END, 'bulk-member-status-search');
