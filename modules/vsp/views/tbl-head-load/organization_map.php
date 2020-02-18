<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use webvimark\modules\UserManagement\models\User;
use yii\web\View;
use yii\helpers\Url;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$title = Yii::$app->label->title('edit', 'user organization');
$button = Yii::$app->label->button('edit');

$this->title = Yii::t('app', $title);
?>
<div class="user-create">
    <div class="panel panel-main">
        <div class="panel-heading"><?php echo Yii::t('app', 'Organization Mapping') ?></div>
        <?php
        $form = ActiveForm::begin([
                    'id' => 'user-org',
                    'validateOnBlur' => false,
        ]);
        ?>
        <div class="panel-body">
            <div class="panel-subheading">
                <h5 class="panel-subtitle"><?php echo $title; ?></h5>
                    <?php echo $form->errorSummary($model); ?>
                <div class="row">
                    <?php //$form->field($model, 'organization', [ 'options' => ['class' => 'form-group col-sm-3']])->dropDownList(['91' => 'National']); ?>
                    <?php echo $form->field($model, 'route', [ 'options' => ['class' => 'form-group col-sm-3',]])->listBox($route['data'], ['multiple' => 'multiple', 'size' => '10', 'options' => $route['selectedArray']]); ?>
                    <?php echo Html::hiddenInput('fed_select', 0, ['id' => 'fed_select']); ?>
                    <?php echo $form->field($model, 'dcs_code', [ 'options' => ['class' => 'form-group col-sm-3',]])->listBox($dcs['data'], ['multiple' => 'multiple', 'size' => '10', 'options' => $dcs['selectedArray']]); ?>
                    <?php echo Html::hiddenInput('union_select', 0, ['id' => 'union_select']); ?>
<?php echo $form->field($model, 'sub_center_code', [ 'options' => ['class' => 'form-group col-sm-3',]])->listBox($subcenter['data'], ['multiple' => 'multiple', 'size' => '10', 'options' => $subcenter['selectedArray']]); ?>
<?php echo Html::hiddenInput('dcs_select', 0, ['id' => 'dcs_select']); ?>
                </div>        
            </div>
        </div>
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?= Yii::$app->controls->save($button, $model); ?>
<?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model); ?>
        </div>

<?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$script = "
    $('#tblheadloadapplicability-route').on('change',function(){          
             var fed = []; 
             $('#tblheadloadapplicability-route :selected').each(function(i, selected){ 
                fed[i] = $(selected).val(); 
              });
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-dcs/get-route-dcs']) . "',    
                        data: 'fed='+fed,
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $('#tblheadloadapplicability-dcs_code').empty();
                                $('#tblheadloadapplicability-sub_center_code').empty();
                                $.each(obj1.data, function(index, value) {
                                    $('#tblheadloadapplicability-dcs_code').append($('<option>').text(value).val(index));
                                });
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });           
    });
    
    $('#tblheadloadapplicability-dcs_code').on('change',function(){          
             var union = []; 
             $('#tblheadloadapplicability-dcs_code :selected').each(function(i, selected){ 
                union[i] = $(selected).val(); 
              });
             var selectedUnion =  union.length;
             var count = $('#tblheadloadapplicability-dcs_code option').length;
             $('#union_select').val(0);
             if(selectedUnion==count)
                $('#union_select').val(1);
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-dcs/get-union-dcs']) . "',    
                        data: 'union='+union,
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $('#tblheadloadapplicability-sub_center_code').empty();
                                $.each(obj1.data, function(index, value) {
                                    $('#tblheadloadapplicability-sub_center_code').append($('<option>').text(value).val(index));
                                });
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });           
    });
    
    $('#tblheadloadapplicability-sub_center_code').on('change',function(){     
        var selectedDcs =  $('#tblheadloadapplicability-sub_center_code :selected').length;
        var count = $('#tblheadloadapplicability-sub_center_code option').length;
        $('#dcs_select').val(0);
        if(selectedDcs==count)
           $('#dcs_select').val(1);
    });
";
$this->registerJs($script, View::POS_END, 'village-code');
?>