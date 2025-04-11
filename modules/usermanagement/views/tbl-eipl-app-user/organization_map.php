<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use softark\duallistbox\DualListbox;

$title = Yii::$app->label->title('edit', 'App user organization');
$button = Yii::$app->label->button('edit');

$this->title = Yii::t('app', $title);
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?php echo Yii::t('app', 'App User-Organization Mapping') . ' => ' . Yii::$app->general->getUserName($user->name); ?>
    </div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'user-org',
                    'validateOnBlur' => TRUE,
        ]);
        ?>

        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?php echo $title; ?></h4>
            </div>
            <?= $form->errorSummary($model); ?>
            <div class="col-md-12">
                <div class="row multiple">
                    <?php
                    echo $form->field($model, 'federation', ['options' => ['class' => 'form-group col-sm-12 hidden',]])
                            ->widget(DualListbox::className(), [
                                'items' => $federations['data'],
                                'clientOptions' => [
                                    'moveOnSelect' => FALSE,
                                    'selectedListLabel' => FALSE,
                                    'nonSelectedListLabel' => FALSE,
                                    'filterPlaceHolder' => '',
                                ],
                    ]);

                    echo $form->field($model, 'union', ['options' => ['class' => 'form-group col-sm-12',]])
                            ->widget(DualListbox::className(), [
                                'items' => $unions['data'],
                                'clientOptions' => [
                                    'moveOnSelect' => FALSE,
                                    'selectedListLabel' => FALSE,
                                    'nonSelectedListLabel' => FALSE,
                                    'filterPlaceHolder' => '',
                                ],
                    ]);

                    echo $form->field($model, 'plant', ['options' => ['class' => 'form-group col-sm-12',]])
                            ->widget(DualListbox::className(), [
                                'items' => $plant['data'],
                                'clientOptions' => [
                                    'moveOnSelect' => FALSE,
                                    'selectedListLabel' => FALSE,
                                    'nonSelectedListLabel' => FALSE,
                                    'filterPlaceHolder' => '',
                                ],
                    ]);
                    echo $form->field($model, 'mcc', ['options' => ['class' => 'form-group col-sm-12',]])
                            ->widget(DualListbox::className(), [
                                'items' => $mcc['data'],
                                'clientOptions' => [
                                    'moveOnSelect' => FALSE,
                                    'selectedListLabel' => FALSE,
                                    'nonSelectedListLabel' => FALSE,
                                    'filterPlaceHolder' => '',
                                ],
                    ]);
                    echo $form->field($model, 'bmc', ['options' => ['class' => 'form-group col-sm-12',]])
                            ->widget(DualListbox::className(), [
                                'items' => $bmc['data'],
                                'clientOptions' => [
                                    'moveOnSelect' => FALSE,
                                    'selectedListLabel' => FALSE,
                                    'nonSelectedListLabel' => FALSE,
                                    'filterPlaceHolder' => '',
                                ],
                    ]);
                    echo $form->field($model, 'dcs', ['options' => ['class' => 'form-group col-sm-12',]])
                            ->widget(DualListbox::className(), [
                                'items' => $dcs['data'],
                                'clientOptions' => [
                                    'moveOnSelect' => FALSE,
                                    'selectedListLabel' => FALSE,
                                    'nonSelectedListLabel' => FALSE,
                                    'filterPlaceHolder' => '',
                                ],
                    ]);
                    ?>
                    <?= Html::hiddenInput('user_type', 2, ['id' => 'user_type']); ?>
                    <div class="clearfix"></div>
                    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                        <div class="form-group">
                            <?= Yii::$app->controls->save($button, $model); ?>
                            <?= Yii::$app->controls->reset(); ?>
                            <?= Yii::$app->controls->cancel($model); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$script = "
    setData();    
    
    $('#tbleipluserorganizationmapping-union').on('change',function(){          
        var union = []; 
        $('#tbleipluserorganizationmapping-union :selected').each(function(i, selected){ 
            union[i] = $(selected).val(); 
        });  
        $.ajax({
                type: 'post',
                url: '" . Url::to(['/organisation/tbl-plant/get-union-plant']) . "',    
                data: 'union='+union+'&RLS=FALSE',
                success: function(data) {
                    var obj1 = $.parseJSON(data);
                    if (obj1.status == 'success')
                    {
                        $('#tbleipluserorganizationmapping-mcc option').remove();                        
                        $('#tbleipluserorganizationmapping-mcc').bootstrapDualListbox('refresh', true);
                        $('#tbleipluserorganizationmapping-bmc option').remove();                        
                        $('#tbleipluserorganizationmapping-bmc').bootstrapDualListbox('refresh', true);
                        $('#tbleipluserorganizationmapping-dcs option').remove();                        
                        $('#tbleipluserorganizationmapping-dcs').bootstrapDualListbox('refresh', true);
            
                        var plantarray =  $('#tbleipluserorganizationmapping-plant option:selected').map(function () {
                            return this.value;
                        }).get();
                        
                        $('#tbleipluserorganizationmapping-plant option').remove();                              
                            var options='';              
                            $.each(obj1.data, function(index, value) {
                                if(jQuery.inArray(index,plantarray) == -1){   
                                    options += '<option value=\"'+index+'\">'+value+'</option>';
                                }else{
                                    options += '<option value=\"'+index+'\" selected>'+value+'</option>';
                                }
                            });  
                        $('#tbleipluserorganizationmapping-plant').html(options);
                        $('#tbleipluserorganizationmapping-plant').bootstrapDualListbox('refresh', true); 
                    }
                    setData();    
                },
                error:function(data){
            }
       });           
    });
    
    $('#tbleipluserorganizationmapping-plant').on('change',function(){          
        var plant = []; 
        $('#tbleipluserorganizationmapping-plant :selected').each(function(i, selected){ 
            plant[i] = $(selected).val(); 
        }); 
        
        $.ajax({
                type: 'post',
                url: '" . Url::to(['/organisation/tbl-mcc-plant/get-plant-mcc']) . "',    
                data: 'plant='+plant+'&RLS=FALSE',
                success: function(data) {
                    var obj1 = $.parseJSON(data);
                    if (obj1.status == 'success')
                    {
                        $('#tbleipluserorganizationmapping-bmc option').remove();                        
                        $('#tbleipluserorganizationmapping-bmc').bootstrapDualListbox('refresh', true);
                        $('#tbleipluserorganizationmapping-dcs option').remove();                        
                        $('#tbleipluserorganizationmapping-dcs').bootstrapDualListbox('refresh', true);
            
                        var mccarray =  $('#tbleipluserorganizationmapping-mcc option:selected').map(function () {
                            return this.value;
                        }).get();
                        
                        $('#tbleipluserorganizationmapping-mcc option').remove();                              
                            var options='';              
                            $.each(obj1.data, function(index, value) {
                                if(jQuery.inArray(index,mccarray) == -1){   
                                    options += '<option value=\"'+index+'\">'+value+'</option>';
                                }else{
                                    options += '<option value=\"'+index+'\" selected>'+value+'</option>';
                                }
                            });                        
                        $('#tbleipluserorganizationmapping-mcc').html(options);
                        $('#tbleipluserorganizationmapping-mcc').bootstrapDualListbox('refresh', true); 
                    }
                    setData();    
                },
                error:function(data){
            }
        });           
    });
    $('#tbleipluserorganizationmapping-mcc').on('change',function(){          
        var mcc = []; 
        $('#tbleipluserorganizationmapping-mcc :selected').each(function(i, selected){ 
            mcc[i] = $(selected).val(); 
        });  
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/organisation/tbl-dcs-bmc/get-mcc-bmc']) . "',    
                    data: 'mcc='+mcc+'&RLS=FALSE',
                    success: function(data) {
                        var obj1 = $.parseJSON(data);
                        if (obj1.status == 'success')
                        {
                           $('#tbleipluserorganizationmapping-dcs option').remove();                        
                           $('#tbleipluserorganizationmapping-dcs').bootstrapDualListbox('refresh', true);
            
                        var mccarray =  $('#tbleipluserorganizationmapping-bmc option:selected').map(function () {
                            return this.value;
                        }).get();
                        
                        $('#tbleipluserorganizationmapping-bmc option').remove();                              
                            var options='';              
                                $.each(obj1.data, function(index, value) {
                                    if(jQuery.inArray(index,mccarray) == -1){   
                                         options += '<option value=\"'+index+'\">'+value+'</option>';
                                    }else{
                                         options += '<option value=\"'+index+'\" selected>'+value+'</option>';
                                    }
                                });                        
                        $('#tbleipluserorganizationmapping-bmc').html(options);
                        $('#tbleipluserorganizationmapping-bmc').bootstrapDualListbox('refresh', true); 
                    }
                    setData();    
                },
                error:function(data){
            }
        });           
    });
    
    $('#tbleipluserorganizationmapping-bmc').on('change',function(){          
        var bmc = []; 
        $('#tbleipluserorganizationmapping-bmc :selected').each(function(i, selected){ 
            bmc[i] = $(selected).val(); 
        });  
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/organisation/tbl-dcs/get-bmc-dcs']) . "',    
                    data: 'bmc='+bmc+'&RLS=FALSE',
                    success: function(data) {
                        var obj1 = $.parseJSON(data);
                        if (obj1.status == 'success')
                        {
                        var dcsarray =  $('#tbleipluserorganizationmapping-dcs option:selected').map(function () {
                            return this.value;
                        }).get();
                            $('#tbleipluserorganizationmapping-dcs option').remove();                              
                                var options='';              
                                $.each(obj1.data, function(index, value) {
                                    if(jQuery.inArray(index,dcsarray) == -1){   
                                         options += '<option value=\"'+index+'\">'+value+'</option>';
                                    }else{
                                         options += '<option value=\"'+index+'\" selected>'+value+'</option>';
                                    }
                                });                        
                        $('#tbleipluserorganizationmapping-dcs').html(options);
                        $('#tbleipluserorganizationmapping-dcs').bootstrapDualListbox('refresh', true); 
                    }
                    setData();    
                },
            error:function(data){
        }
    });           
});
    
$('#tbleipluserorganizationmapping-dcs').on('change',function(){     
    setData();
});
    
function setData(){
    var selectedDcs =  $('#tbleipluserorganizationmapping-dcs :selected').length;
    var count = $('#tbleipluserorganizationmapping-dcs option').length;
    if(count > 0 && selectedDcs != 0){
        $('#user_type').val(7);
        if(selectedDcs==count) 
        $('#user_type').val(6);
    }else{
        var selectedBmc =  $('#tbleipluserorganizationmapping-bmc :selected').length;
        var count = $('#tbleipluserorganizationmapping-bmc option').length;
        if(count > 0 && selectedBmc != 0){
            $('#user_type').val(6);
            if(selectedBmc==count) 
            $('#user_type').val(5);
        }else{
            var selectedMcc =  $('#tbleipluserorganizationmapping-mcc :selected').length;
            var count = $('#tbleipluserorganizationmapping-mcc option').length;
            if(count > 0 && selectedMcc != 0){
                $('#user_type').val(5);
                if(selectedMcc==count)  
                $('#user_type').val(4);
            } else {
                var selectedPlant =  $('#tbleipluserorganizationmapping-plant :selected').length;
                var count = $('#tbleipluserorganizationmapping-plant option').length;
                if(count > 0 && selectedPlant != 0){
                    $('#user_type').val(4);
                    if(selectedPlant==count) 
                    $('#user_type').val(3);
                } else {
                    var selectedUnion =  $('#tbleipluserorganizationmapping-union :selected').length;
                    var count = $('#tbleipluserorganizationmapping-union option').length;             
                    if(count > 0 && selectedUnion != 0){
                    $('#user_type').val(3);
                       //                if(selectedUnion==count) {
                       //                $('#user_type').val(2);
                       //                }
                    } else {             
                        $('#user_type').val(2);
                    } 
                } 
            }
        }  
    }        
}        

";
$this->registerJs($script, View::POS_END, 'user-org-map-list');
?>