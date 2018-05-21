<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use softark\duallistbox\DualListbox;

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
                    'validateOnBlur' => TRUE,
        ]);
        ?>

        <h5 class="panel-subtitle"><?php echo $title; ?></h5>
        <?= $form->errorSummary($model); ?>
        <div class="row">
            <?php
            echo $form->field($model, 'federation', [ 'options' => ['class' => 'form-group col-sm-12 hidden',]])
                    ->widget(DualListbox::className(), [
                        'items' => $federations['data'],
                        'clientOptions' => [
                            'moveOnSelect' => FALSE,
                            'selectedListLabel' => FALSE,
                            'nonSelectedListLabel' => FALSE,
                            'filterPlaceHolder' => '',
                        ],
            ]);

            echo $form->field($model, 'union', [ 'options' => ['class' => 'form-group col-sm-12',]])
                    ->widget(DualListbox::className(), [
                        'items' => $unions['data'],
                        'clientOptions' => [
                            'moveOnSelect' => FALSE,
                            'selectedListLabel' => FALSE,
                            'nonSelectedListLabel' => FALSE,
                            'filterPlaceHolder' => '',
                        ],
            ]);

            echo $form->field($model, 'plant', [ 'options' => ['class' => 'form-group col-sm-12',]])
                    ->widget(DualListbox::className(), [
                        'items' => $plant['data'],
                        'clientOptions' => [
                            'moveOnSelect' => FALSE,
                            'selectedListLabel' => FALSE,
                            'nonSelectedListLabel' => FALSE,
                            'filterPlaceHolder' => '',
                        ],
            ]);
            echo $form->field($model, 'mcc', [ 'options' => ['class' => 'form-group col-sm-12',]])
                    ->widget(DualListbox::className(), [
                        'items' => $mcc['data'],
                        'clientOptions' => [
                            'moveOnSelect' => FALSE,
                            'selectedListLabel' => FALSE,
                            'nonSelectedListLabel' => FALSE,
                            'filterPlaceHolder' => '',
                        ],
            ]);
            echo $form->field($model, 'bmc', [ 'options' => ['class' => 'form-group col-sm-12',]])
                    ->widget(DualListbox::className(), [
                        'items' => $bmc['data'],
                        'clientOptions' => [
                            'moveOnSelect' => FALSE,
                            'selectedListLabel' => FALSE,
                            'nonSelectedListLabel' => FALSE,
                            'filterPlaceHolder' => '',
                        ],
            ]);
            echo $form->field($model, 'dcs', [ 'options' => ['class' => 'form-group col-sm-12',]])
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
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$script = "
    setData();    
    $('#tbluserorganizationmapping-union').on('change',function(){          
             var union = []; 
             $('#tbluserorganizationmapping-union :selected').each(function(i, selected){ 
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
                          $('#tbluserorganizationmapping-mcc option').remove();                        
                          $('#tbluserorganizationmapping-mcc').bootstrapDualListbox('refresh', true);
                          $('#tbluserorganizationmapping-bmc option').remove();                        
                          $('#tbluserorganizationmapping-bmc').bootstrapDualListbox('refresh', true);
                          $('#tbluserorganizationmapping-dcs option').remove();                        
                          $('#tbluserorganizationmapping-dcs').bootstrapDualListbox('refresh', true);
            
                        var plantarray =  $('#tbluserorganizationmapping-plant option:selected').map(function () {
                                        return this.value;
                                      }).get();
                           $('#tbluserorganizationmapping-plant option').remove();                              
                               var options='';              
                                $.each(obj1.data, function(index, value) {
                                    if(jQuery.inArray(index,plantarray) == -1){   
                                         options += '<option value=\"'+index+'\">'+value+'</option>';
                                    }else{
                                         options += '<option value=\"'+index+'\" selected>'+value+'</option>';
                                    }
                                });  
                       $('#tbluserorganizationmapping-plant').html(options);
                        $('#tbluserorganizationmapping-plant').bootstrapDualListbox('refresh', true); 
                            }
                         setData();    
                        },
                        error:function(data){
                                }
            });           
    });
    $('#tbluserorganizationmapping-plant').on('change',function(){          
             var plant = []; 
             $('#tbluserorganizationmapping-plant :selected').each(function(i, selected){ 
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
                          $('#tbluserorganizationmapping-bmc option').remove();                        
                          $('#tbluserorganizationmapping-bmc').bootstrapDualListbox('refresh', true);
                          $('#tbluserorganizationmapping-dcs option').remove();                        
                          $('#tbluserorganizationmapping-dcs').bootstrapDualListbox('refresh', true);
            
                        var mccarray =  $('#tbluserorganizationmapping-mcc option:selected').map(function () {
                                        return this.value;
                                      }).get();
                           $('#tbluserorganizationmapping-mcc option').remove();                              
                               var options='';              
                                $.each(obj1.data, function(index, value) {
                                    if(jQuery.inArray(index,mccarray) == -1){   
                                         options += '<option value=\"'+index+'\">'+value+'</option>';
                                    }else{
                                         options += '<option value=\"'+index+'\" selected>'+value+'</option>';
                                    }
                                });                        
                       $('#tbluserorganizationmapping-mcc').html(options);
                        $('#tbluserorganizationmapping-mcc').bootstrapDualListbox('refresh', true); 
                            }
                         setData();    
                        },
                        error:function(data){
                                }
            });           
    });
    $('#tbluserorganizationmapping-mcc').on('change',function(){          
             var mcc = []; 
             $('#tbluserorganizationmapping-mcc :selected').each(function(i, selected){ 
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
                           $('#tbluserorganizationmapping-dcs option').remove();                        
                          $('#tbluserorganizationmapping-dcs').bootstrapDualListbox('refresh', true);
            
                        var mccarray =  $('#tbluserorganizationmapping-bmc option:selected').map(function () {
                                        return this.value;
                                      }).get();
                           $('#tbluserorganizationmapping-bmc option').remove();                              
                               var options='';              
                                $.each(obj1.data, function(index, value) {
                                    if(jQuery.inArray(index,mccarray) == -1){   
                                         options += '<option value=\"'+index+'\">'+value+'</option>';
                                    }else{
                                         options += '<option value=\"'+index+'\" selected>'+value+'</option>';
                                    }
                                });                        
                       $('#tbluserorganizationmapping-bmc').html(options);
                        $('#tbluserorganizationmapping-bmc').bootstrapDualListbox('refresh', true); 
                            }
                         setData();    
                        },
                        error:function(data){
                                }
            });           
    });
    $('#tbluserorganizationmapping-bmc').on('change',function(){          
             var bmc = []; 
             $('#tbluserorganizationmapping-bmc :selected').each(function(i, selected){ 
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
                        var dcsarray =  $('#tbluserorganizationmapping-dcs option:selected').map(function () {
                                        return this.value;
                                      }).get();
                           $('#tbluserorganizationmapping-dcs option').remove();                              
                               var options='';              
                                $.each(obj1.data, function(index, value) {
                                    if(jQuery.inArray(index,dcsarray) == -1){   
                                         options += '<option value=\"'+index+'\">'+value+'</option>';
                                    }else{
                                         options += '<option value=\"'+index+'\" selected>'+value+'</option>';
                                    }
                                });                        
                       $('#tbluserorganizationmapping-dcs').html(options);
                        $('#tbluserorganizationmapping-dcs').bootstrapDualListbox('refresh', true); 
                            }
                         setData();    
                        },
                        error:function(data){
                                }
            });           
    });
    $('#tbluserorganizationmapping-dcs').on('change',function(){     
       setData();
    });
function setData(){
          var selectedDcs =  $('#tbluserorganizationmapping-dcs :selected').length;
          var count = $('#tbluserorganizationmapping-dcs option').length;
            if(count > 0 && selectedDcs != 0){
                $('#user_type').val(7);
                if(selectedDcs==count) 
                $('#user_type').val(6);
              }else{
              
 var selectedBmc =  $('#tbluserorganizationmapping-bmc :selected').length;
           var count = $('#tbluserorganizationmapping-bmc option').length;
             if(count > 0 && selectedBmc != 0){
                $('#user_type').val(6);
                if(selectedBmc==count) 
                $('#user_type').val(5);
              }else{
              var selectedMcc =  $('#tbluserorganizationmapping-mcc :selected').length;
           var count = $('#tbluserorganizationmapping-mcc option').length;
             if(count > 0 && selectedMcc != 0){
                $('#user_type').val(5);
                if(selectedMcc==count)  
                $('#user_type').val(4);
              }  else {
               var selectedPlant =  $('#tbluserorganizationmapping-plant :selected').length;
           var count = $('#tbluserorganizationmapping-plant option').length;
             if(count > 0 && selectedPlant != 0){
                $('#user_type').val(4);
                if(selectedPlant==count) 
                $('#user_type').val(3);
              } else {
               var selectedUnion =  $('#tbluserorganizationmapping-union :selected').length;
           var count = $('#tbluserorganizationmapping-union option').length;             
             if(count > 0 && selectedUnion != 0){
              $('#user_type').val(3);
                if(selectedUnion==count) {
                $('#user_type').val(2);
                }
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