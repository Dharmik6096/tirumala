<?php
//use yii\widgets\ActiveForm;
//use yii\web\View;
?>

<!--<div class="tbl-milk-collection-search">

<?php
//    $form = ActiveForm::begin([
//                'options' => [
//                    'field-class' => 'form-group col-sm-2 padding-right-5',
//                    'id' => 'document-mapping-search',
//                ],
//                'method' => 'get',
//    ]);
?>
    <div class="col-sm-2">
        <? Yii::$app->dropdown->dropdownStatic('master_type', $model, $form, 'form-group', $model->getAttributeLabel('master_type'), false, 'master_type', false); ?>
    </div>
<?php// ActiveForm::end(); ?>

</div>-->
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
        <?= Yii::$app->dropdown->dropdownStatic('master_type', $model, $form, 'form-group', $model->getAttributeLabel('master_type'), false, 'master_type', false); ?>
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
        var login = $('#tbldocumentmapping-master_type').val();
        if(login =='farmer') {
            $('form#document-mapping-search').submit();
             setTimeout(function() {
                 $('.showHideData').show();
             },2000);
        } else if(login !='') {
             $('form#document-mapping-search').submit();
             setTimeout(function() {
             $('.showHideData').show();
              },2000);
        } else {
            $('.showHideData').hide();
        }
    } 

     function updateGrid() {
        var login = $('#tbldocumentmapping-master_type').val();
        if(login =='farmer') {
            $('.showHideData').show();
        } else if(login !='') {
            $('.showHideData').show();
        } else {
            $('.showHideData').hide();
        }
    }
    
     ";
$this->registerJs($script, View::POS_END, 'bulk-member-status-search');
