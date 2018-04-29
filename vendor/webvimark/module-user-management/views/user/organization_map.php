<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$title = Yii::$app->label->title('edit', 'user organization');
$button = Yii::$app->label->button('edit');

$this->title = Yii::t('app', $title);
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?php echo Yii::t('app', 'User-Organization Mapping') . ' => ' . Yii::$app->general->getUserName($user->username); ?>
    </div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'user-org',
                    'validateOnBlur' => false,
        ]);
        ?>

        <h5 class="panel-subtitle"><?php echo $title; ?></h5>
        <?= $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'federation')->listBox($federations['data'], ['multiple' => 'multiple', 'size' => '10', 'options' => $federations['selectedArray']]); ?>
            </div>            
            <div class="col-sm-3">
                <?= $form->field($model, 'union')->listBox($unions['data'], ['multiple' => 'multiple', 'size' => '10', 'options' => $unions['selectedArray']]); ?>
            </div>           
            <div class="col-sm-3">
                <?= $form->field($model, 'dcs')->listBox($dcs['data'], ['multiple' => 'multiple', 'size' => '10', 'options' => $dcs['selectedArray']])->label('Society'); ?>
            </div>
            <?= Html::hiddenInput('user_type', 0, ['id' => 'user_type']); ?>

            <div class="clearfix"></div>
            <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save($button, $model); ?>
                    <?= Yii::$app->controls->reset(); ?>
                    <?= Yii::$app->controls->cancel($model); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$script = "
    $('#tbluserorganizationmapping-federation').on('change',function(){           
             var fed = []; 
             $('#tbluserorganizationmapping-federation :selected').each(function(i, selected){ 
                fed[i] = $(selected).val(); 
              });
              $('#user_type').val(2);
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-unions/get-federation-unions']) . "',
                        data: 'fed='+fed,
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $('#tbluserorganizationmapping-union').empty();
                                $('#tbluserorganizationmapping-dcs').empty();
                                $.each(obj1.data, function(index, value) {
                                    $('#tbluserorganizationmapping-union').append($('<option>').text(value).val(index));
                                });
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });           
    });
    
    $('#tbluserorganizationmapping-union').on('change',function(){          
             var union = []; 
             $('#tbluserorganizationmapping-union :selected').each(function(i, selected){ 
                union[i] = $(selected).val(); 
              });            
             $('#user_type').val(3);
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-dcs/get-union-dcs']) . "',
                        data: 'union='+union,
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $('#tbluserorganizationmapping-dcs').empty();
                                $.each(obj1.data, function(index, value) {
                                    $('#tbluserorganizationmapping-dcs').append($('<option>').text(value).val(index));
                                });
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });           
    });
    
    $('#tbluserorganizationmapping-dcs').on('change',function(){ 
        $('#user_type').val(4);
    });
";
$this->registerJs($script, View::POS_END, 'village-code');
?>