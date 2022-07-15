<?php

use yii\web\View;
use yii\helpers\Url;
?>
<div id="maincontent">
    <?=
    $this->render('_entry_input', ['model' => $model, 'type' => 'create']);
    ?>
</div>
<div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
    <div class="QltyParamDivGrid">
        <?=
        $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider])
        ?>
    </div>
</div>
<?php
$script = "
     gridChange();
    $(document).on('change', '#tblgateentry-date_time_of_collection', function() {  
        gridChange();
    });
    $(document).on('change', '#tblgateentry-shift_code', function() {  
        gridChange();
    });
    $(document).on('change', '#tblgateentry-bmc_code', function() { 
         gridChange();
    });
    $(document).on('change', '#tblgateentry-route_code', function() { 
         routeChange();
    });
    function gridChange(){
       $('.add-collection').prop('disabled',true);
       $('#gate-entry-form .reset_field input').val('');
       $('.QltyParamDiv').hide();
        var bmc = $('#tblgateentry-bmc_code').val();
        var date = $('#tblgateentry-date_time_of_collection').val();
        var shift = $('#tblgateentry-shift_code').val();
            if(setData(bmc) && setData(date) && setData(shift)){
                $('.add-collection').removeAttr('disabled');
        }        
    }
    function routeChange(){
        var route = $('#tblgateentry-route_code').val();
        var date = $('#tblgateentry-date_time_of_collection').val();
        var shift = $('#tblgateentry-shift_code').val();
            if(setData(route) && setData(date) && setData(shift)){
            vehicleDetail(route,date,shift);
        }        
    }
    function setData(field = ''){
        if(field != '' && field != null && field != undefined){
            return true;
        }else {
            return false;
        }
    }
    
    $(document).on('click','.add-collection',function(e){
        reloadGrid();
         $('#gate-entry-form .reset_field input').val('');
         $('#gate-entry-form .reset_field select').val('');                                                             
         $('#tblgateentry-route_code').change();
         $('.QltyParamDiv').show();
    });
     $(document).on('click','.edit-record',function(e){
     var id = $(this).attr('data-val');
     var url = '" . Url::to(['/transporter/tbl-gate-entry/update']) . "';
                $.ajax({
                    type: 'get',
                    data:{'id':id},
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        var obj = $.parseJSON(data);
                        if(obj.status=='success'){
                        $('#tblgateentry-route_code').val(obj.route_code).change();
                        $('#tblgateentry-gate_entry_code').val(id);
                        $('#tblgateentry-actual_arrival_time').val(obj.actual_arrival_time);
                        $('#tblgateentry-route_code').prop('disabled', true);
                        $('#tblgateentry-vehicle_code').prop('disabled', true);
                        }
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });         
    });
    function reloadGrid(){
            var url = '" . Url::to(['/transporter/tbl-gate-entry/list-grid']) . "'+ '?' + $('#gate-entry-form').serialize();
                $.ajax({
                    type: 'get',
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#gridcontentSet .QltyParamDivGrid').html(data);
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
    function vehicleDetail(route,date,shift){
            var url = '" . Url::to(['/transporter/tbl-vehicle-km-info/route-vehicle-detail']) . "';
                $.ajax({
                    type: 'get',
                    data:{'route_code':route,'date':date,'shift_code':shift},
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        var obj = $.parseJSON(data);
                        $('#tblgateentry-define_arrival_time').val(obj.arrival_time);
                        $('#tblgateentry-grace_time').val(obj.grace_time);
                        $('#tblgateentry-vehicle_code').find('option').remove().end().append($('<option></option>').attr('value',obj.vehicle_code).text(obj.parsing_no));
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
";
$this->registerJs($script, View::POS_END, 'gate-entry-create');
?>
