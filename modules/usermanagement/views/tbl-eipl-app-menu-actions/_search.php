<?php

use yii\widgets\ActiveForm;
use yii\web\View;
?>

<div class="tbl-milk-collection-search">

    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'field-class' => 'form-group col-sm-2 padding-right-5',
                    'id' => 'app-menu-mapping-search',
                ],
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
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
    $('#tbleiplappmenuactionsmapping-login_type').change(function() {
        submitForm();
    });
    $('#tbleiplappmenuactionsmapping-department').change(function() {
        submitForm();
    });
    $('#tbleiplappmenuactionsmapping-union_code').change(function() {
        submitForm();
    });

    function submitForm(){
        var login = $('#tbleiplappmenuactionsmapping-login_type').val();
        var department = $('#tbleiplappmenuactionsmapping-department').val(); 
        var unionCode = $('#tbleiplappmenuactionsmapping-union_code').val();
        if (!unionCode) {
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span> Please select Union Code.</span></div></div>');
            return false;
        }
        if(login =='farmer') {
            $('#tbleiplappmenuactionsmapping-department').val('');
            $('.department_div').hide();
            $('form#app-menu-mapping-search').submit();
             setTimeout(function() {
                 $('.showHideData').show();
             },1000);
        } else if(login !='') {
             $('.department_div').show();
             $('form#app-menu-mapping-search').submit();
             setTimeout(function() {
             $('.showHideData').show();
              },1000);
        } else {
            $('.showHideData').hide();
        }
    } 
    function updateGrid() {
    $('.department_div').hide();
        var login = $('#tbleiplappmenuactionsmapping-login_type').val();
        var department = $('#tbleiplappmenuactionsmapping-department').val();
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
$this->registerJs($script, View::POS_END, 'app-menu-mapping-search');
