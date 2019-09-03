<?php

use yii\widgets\ActiveForm;
use yii\web\View;
?>

<div class="tbl-sub-center-search">

    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'field-class' => 'form-group col-sm-2 padding-right-5'
                ],
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>

    <?php Yii::$app->dropdown->federation($model, $form, 'federation_code', false); ?>

    <?= Yii::$app->dropdown->union($model, $form, 'tblpendriveimportexportsearch-federation_code', 'union_code', false); ?>

    <?= Yii::$app->dropdown->dcs($model, $form, 'tblpendriveimportexportsearch-union_code', 'dcs_code', false); ?>

    <?= Yii::$app->dropdown->sub_center($model, $form, 'tblpendriveimportexportsearch-dcs_code', 'sub_center_code', false); ?>

    <div class="form-group col-sm-3 padding-left-5">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = "
   $(document).ready(function() {
         var id='" . strtolower((new ReflectionClass($model))->getShortName() . '-federation_code') . "';   
         $('#'+id+' option:selected').val('" . Yii::$app->session->get('Federations') . "');
         $('#'+id).parent('div').hide(); 
         id='" . strtolower((new ReflectionClass($model))->getShortName() . '-union_code') . "';             
         $('#'+id+' option:selected').val('" . Yii::$app->session->get('Unions') . "');
         $('#'+id).parent('div').hide();
         
    });
";
$this->registerJs($script, View::POS_END, 'search');
?>