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

        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?php echo $title; ?></h4>
            </div>
            <?= $form->errorSummary($model); ?>
            <div class="col-md-12">
                <div class="row multiple" id ="hieghtAdjust" data-pluseheigt = "120" data-minuse = "120">
                    <?php
                    $dualListBoxes = [
                        'federation' => ['data' => $federations['data'], 'hidden' => true],
                        'union' => ['data' => $unions['data']],
                    ];

                    if ($user->is_engineer != 1) {
                        $dualListBoxes['plant'] = ['data' => $plant['data']];
                        $dualListBoxes['mcc'] = ['data' => $mcc['data']];
                        $dualListBoxes['bmc'] = ['data' => $bmc['data']];
                        $dualListBoxes['route'] = ['data' => $route['data']];
                        $dualListBoxes['dcs'] = ['data' => $dcs['data']];
                    }
                    ?>
                    <div class="row collapse-toggle-buttons margin-bottom-10">
                        <?php
                        foreach ($dualListBoxes as $field => $options):
                            $collapseId = "collapse-" . $field;
                            $label = $model->getAttributeLabel($field);
                            $hiddenClass = isset($options['hidden']) && $options['hidden'] ? 'hidden' : '';
                            ?>
                            <div class="btn-group margin-right-5 <?= $hiddenClass ?>">
                                <button type="button" class="collapsible-btn" data-toggle="collapse" data-target="#<?= $collapseId ?>">- <?= $label ?></button>

                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php
                    foreach ($dualListBoxes as $field => $options):
                        $collapseId = "collapse-" . $field;
                        $hiddenClass = isset($options['hidden']) && $options['hidden'] ? 'hidden' : '';
                        ?>
                        <div class="col-sm-12 <?= $hiddenClass ?>">
                            <div id="<?= $collapseId ?>" class="collapse in">
                                <?=
                                $form->field($model, $field, [
                                    'options' => ['class' => 'form-group col-sm-12'],
                                ])->widget(DualListbox::className(), [
                                    'items' => $options['data'],
                                    'clientOptions' => [
                                        'moveOnSelect' => FALSE,
                                        'selectedListLabel' => FALSE,
                                        'nonSelectedListLabel' => FALSE,
                                        'filterPlaceHolder' => '',
                                    ],
                                ])
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?= Html::hiddenInput('user_type', 2, ['id' => 'user_type']); ?>
                    <div class="clearfix"></div>
                    <?php if (!$hideControlsBtn) { ?>
                        <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                            <div class="form-group">
                                <?= Yii::$app->controls->save($button, $model); ?>
                                <?= Yii::$app->controls->reset(); ?>
                                <?= Yii::$app->controls->cancel($model); ?>
                            </div>
                        </div>
                    <?php } ?>
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
                        data: 'union='+union+'&RLS=TRUE',
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
                        data: 'plant='+plant+'&RLS=TRUE',
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
                        data: 'mcc='+mcc+'&RLS=TRUE',
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
          getDCSData();
          getRouteData();
    });
    $('#tbluserorganizationmapping-route').on('change',function(){  
          getDCSData();
    });
    
    function getDCSData(){
        var bmc = []; 
        var route = []; 
            $('#tbluserorganizationmapping-bmc :selected').each(function(i, selected){ 
                bmc[i] = $(selected).val(); 
            }); 
            $('#tbluserorganizationmapping-route :selected').each(function(i, selected){ 
                route[i] = $(selected).val(); 
            }); 
            $.ajax({
             type: 'post',
                        url: '" . Url::to(['/organisation/tbl-dcs/get-bmc-dcs']) . "',    
                        data: 'bmc='+bmc+'&route='+route+'&RLS=TRUE',
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
    }
    function getRouteData(){
        var bmc = []; 
        var mcc = []; 
        var plant = []; 
            $('#tbluserorganizationmapping-bmc :selected').each(function(i, selected){ 
                bmc[i] = $(selected).val(); 
            }); 
            $('#tbluserorganizationmapping-mcc :selected').each(function(i, selected){ 
                mcc[i] = $(selected).val(); 
            }); 
            $('#tbluserorganizationmapping-plant :selected').each(function(i, selected){ 
                plant[i] = $(selected).val(); 
            }); 
            $.ajax({
             type: 'post',
                        url: '" . Url::to(['/organisation/tbl-route-mapping/get-bmc-route']) . "',    
                        data: 'bmc='+bmc+'&mcc='+mcc+'&plant='+plant+'&RLS=TRUE',
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                        var routearray =  $('#tbluserorganizationmapping-route option:selected').map(function () {
                                        return this.value;
                                      }).get();
                           $('#tbluserorganizationmapping-route option').remove();                              
                               var options='';              
                                $.each(obj1.data, function(index, value) {
                                    if(jQuery.inArray(index,routearray) == -1){   
                                         options += '<option value=\"'+index+'\">'+value+'</option>';
                                    }else{
                                         options += '<option value=\"'+index+'\" selected>'+value+'</option>';
                                    }
                                });                        
                       $('#tbluserorganizationmapping-route').html(options);
                        $('#tbluserorganizationmapping-route').bootstrapDualListbox('refresh', true); 
                            }
                         setData();    
                        },
                        error:function(data){
                                }
            });    
    }
    $('#tbluserorganizationmapping-dcs').on('change',function(){     
       setData();
    });
    function setData(){
            var selectedDcs =  $('#tbluserorganizationmapping-dcs :selected').length;
            var count = $('#tbluserorganizationmapping-dcs option').length;
            var routeCount = $('#tbluserorganizationmapping-route option').length;
            var selectedRoute =  $('#tbluserorganizationmapping-route :selected').length;
            
            if(count > 0 && selectedDcs != 0){
                $('#user_type').val(7);
                if(routeCount == selectedRoute && selectedDcs == count){
                    $('#user_type').val(7);
                }else if(routeCount == selectedRoute && selectedDcs != count){
                    $('#user_type').val(7);
                }else if(selectedRoute != 0 && routeCount != selectedRoute && selectedDcs == count){
                    $('#user_type').val(7);
                }else             
                if(routeCount==selectedRoute){
                    $('#user_type').val(6);
                }
            }else{
                var selectedBmc =  $('#tbluserorganizationmapping-bmc :selected').length;
                var count = $('#tbluserorganizationmapping-bmc option').length;
                if(count > 0 && selectedBmc != 0){
                   $('#user_type').val(6);
//                   if(selectedBmc==count) 
//                   $('#user_type').val(5);
                }else{
                    var selectedMcc =  $('#tbluserorganizationmapping-mcc :selected').length;
                    var count = $('#tbluserorganizationmapping-mcc option').length;
                    if(count > 0 && selectedMcc != 0){
                       $('#user_type').val(5);
//                       if(selectedMcc==count)  
//                       $('#user_type').val(4);
                    }  else {
                        var selectedPlant =  $('#tbluserorganizationmapping-plant :selected').length;
                        var count = $('#tbluserorganizationmapping-plant option').length;
                        if(count > 0 && selectedPlant != 0){
                           $('#user_type').val(4);
//                           if(selectedPlant==count) 
//                           $('#user_type').val(3);
                        } else {
                            var selectedUnion =  $('#tbluserorganizationmapping-union :selected').length;
                            var count = $('#tbluserorganizationmapping-union option').length;             
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

function adjustDualListboxHeight(increase = false) {
        var currentPluseHeight = $('#hieghtAdjust').data('pluseheigt');
        var currentMinuesHeight = $('#hieghtAdjust').data('minuse');
        
        var changesHeight = Math.min(currentPluseHeight + 50, 370);
        var changeMinHeight = Math.max(currentMinuesHeight - 50, 120);
 
        $('.bootstrap-duallistbox-container select[multiple]').each(function () {
            const newHeight = increase ? changeMinHeight : changesHeight;
            $(this).css('height', newHeight + 'px');
        });
        
        if(increase){
            $('#hieghtAdjust').data('minuse', changeMinHeight);
            $('#hieghtAdjust').data('pluseheigt', changeMinHeight);
        }else{
            $('#hieghtAdjust').data('pluseheigt', changesHeight);
            $('#hieghtAdjust').data('minuse', changesHeight);
        }
    }

    $(document).on('click', '.collapsible-btn', function() {
        let \$button = $(this);
        let target = \$button.attr('data-target');
        let label = \$button.text().substring(1);
       $(target).off('shown.bs.collapse').on('shown.bs.collapse', function () {
        \$button.text('-' + label);
        adjustDualListboxHeight(true);
       
    });

    $(target).off('hidden.bs.collapse').on('hidden.bs.collapse', function () {
       \$button.text('+' + label);
      adjustDualListboxHeight(false); 
   });
});
";
$this->registerJs($script, View::POS_END, 'user-org-map-list');
?>
