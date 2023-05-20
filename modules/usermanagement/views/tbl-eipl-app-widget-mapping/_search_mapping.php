<?php

use yii\widgets\ActiveForm;
use yii\web\View;
?>

<div class="tbl-milk-collection-search">

    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'field-class' => 'form-group col-sm-2 padding-right-5',
                    'id' => 'app-widget-mapping-search',
                ],
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('login_type_ho_flutter', $model, $form, 'form-group', $model->getAttributeLabel('login_type'), false, 'login_type', false); ?>
    </div>
    <div class="col-sm-2 department_div">
        <?= Yii::$app->dropdown->dropdown('department', $model, $form, '', $model->getAttributeLabel('department'), false, 'department'); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
$script = "
    updateGrid();
    $('#tbleiplappwidgetmapping-login_type').change(function() {
        submitForm();
    });
    $('#tbleiplappwidgetmapping-department').change(function() {
        submitForm();
    });

    function submitForm(){
        var login = $('#tbleiplappwidgetmapping-login_type').val();
        var department = $('#tbleiplappwidgetmapping-department').val();
        if(login =='farmer') {
            $('#tbleiplappwidgetmapping-department').val('');
            $('.department_div').hide();
            $('form#app-widget-mapping-search').submit();
             setTimeout(function() {
                 $('.showHideData').show();
             },2000);
        } else if(login !='') {
             $('.department_div').show();
             $('form#app-widget-mapping-search').submit();
             setTimeout(function() {
             $('.showHideData').show();
              },2000);
        } else {
            $('.showHideData').hide();
        }
    } 

     function updateGrid() {
    $('.department_div').hide();
        var login = $('#tbleiplappwidgetmapping-login_type').val();
        var department = $('#tbleiplappwidgetmapping-department').val();
        if(login =='farmer') {
            $('.showHideData').show();
        } else if(login !='') {
            $('.showHideData').show();
            $('.department_div').show();
        } else {
            $('.showHideData').hide();
        }
    }
    
     ";
$this->registerJs($script, View::POS_END, 'bulk-member-status-search');
