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
        <?= Yii::$app->dropdown->dropdownStatic('app_type', $model, $form, '', $model->getAttributeLabel('app_type'), false, 'app_type'); ?>
    </div>
    <div class="col-sm-2 login_type_div">
        <?= Yii::$app->dropdown->dropdownStatic('login_type', $model, $form, '', $model->getAttributeLabel('login_type'), false, 'login_type', false, true); ?>
    </div>
    <div class="col-sm-2 department_div">
        <?= Yii::$app->dropdown->dropdown('department', $model, $form, '', $model->getAttributeLabel('department'), false, 'department'); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
$script = "
    var selectedLoginType = '" . ($model->login_type ?? '') . "';
    updateGrid();
    manageDriverOption();
    $('#tbleiplappmenuactionsmapping-app_type').change(function() {
        manageDriverOption();
        $('.login_type_div').show();
        submitForm();
    });
    $('#tbleiplappmenuactionsmapping-login_type').change(function() {
        selectedLoginType = $(this).val();
        submitForm();
    });
    $('#tbleiplappmenuactionsmapping-department').change(function() {
        submitForm();
    });
    $('#tbleiplappmenuactionsmapping-union_code').change(function() {
        submitForm();
    });

    function manageDriverOption() {
        var appType = $('#tbleiplappmenuactionsmapping-app_type').val();
        var loginDropdown = $('#tbleiplappmenuactionsmapping-login_type');
        if (appType == 4) {
            if (loginDropdown.find('option[value=\"DRIVER\"]').length == 0) {
                loginDropdown.append('<option value=\"DRIVER\">Driver</option>');
            }
            if (selectedLoginType === 'DRIVER') {
                loginDropdown.val('DRIVER');
            }
        } else {
            loginDropdown.find('option[value=\"DRIVER\"]').remove();
        }
        updateGrid();
    }

    function submitForm(){
        var appType = $('#tbleiplappmenuactionsmapping-app_type').val();
        var login = $('#tbleiplappmenuactionsmapping-login_type').val();
        var department = $('#tbleiplappmenuactionsmapping-department').val(); 
        var unionCode = $('#tbleiplappmenuactionsmapping-union_code').val();
        if (!unionCode) {
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span> Please select Union Code.</span></div></div>');
            return false;
        }
        if(appType != '' && appType != null && appType != undefined && appType != 'Loading ...'){
            if(appType == 4) {
                $('.login_type_div').show();
                if (login == 'DRIVER') {
                    $('#tbleiplappmenuactionsmapping-department').val('DRIVER');
                    $('.department_div').hide();
                } else {
                    if ($('#tbleiplappmenuactionsmapping-department').val() == 'DRIVER') {
                        $('#tbleiplappmenuactionsmapping-department').val('');
                    }
                    $('.department_div').show();
                }
                $('form#app-menu-mapping-search').submit();
                setTimeout(function() {
                    $('.showHideData').show();
                },1000);
            } else if(login == 'farmer' || login == 'MEMBER') {
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
        } else {
            $('#tbleiplappmenuactionsmapping-login_type').val('');
            $('#tbleiplappmenuactionsmapping-department').val(''); 
            $('.department_div').hide();
            $('.login_type_div').hide();
            $('.showHideData').hide();
            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span> Please select App Type.</span></div></div>');
            return false;
        }
    } 
    function updateGrid() {
        $('.department_div').hide();
        var appType = $('#tbleiplappmenuactionsmapping-app_type').val();
        var login = $('#tbleiplappmenuactionsmapping-login_type').val();
        var department = $('#tbleiplappmenuactionsmapping-department').val();
        if(appType == 4) {
            $('.login_type_div').show();
            if (login == 'DRIVER') {
                $('.department_div').hide();
            } else {
                $('.department_div').show();
            }
            $('.showHideData').show();
        } else if(login =='farmer' || login == 'MEMBER') {
            $('.showHideData').show();
        } else if(login !='') {
            if(login == 'DRIVER'){
                $('#tbleiplappmenuactionsmapping-login_type').val('');
            }
            $('.showHideData').show();
            $('.department_div').show();
        } else {
            $('.showHideData').hide();
        }
    }
  
     ";
$this->registerJs($script, View::POS_END, 'app-menu-mapping-search');
