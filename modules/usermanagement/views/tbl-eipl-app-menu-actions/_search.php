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
        <?= Yii::$app->dropdown->dropdownStatic('user_login_type', $model, $form, 'form-group', $model->getAttributeLabel('login_type'), false, 'login_type', false, TRUE); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
$script = "
    updateGrid();
    $('#tbleiplappmenuactionsmapping-login_type').change(function() {
        submitForm();
    });

    function submitForm(){
        var login = $('#tbleiplappmenuactionsmapping-login_type').val();
      if(login !='') {
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
        if(login !='') {
            $('.showHideData').show();
        }   
        else {
            $('.showHideData').hide();
        }
    }
  
     ";
$this->registerJs($script, View::POS_END, 'app-menu-mapping-search');

