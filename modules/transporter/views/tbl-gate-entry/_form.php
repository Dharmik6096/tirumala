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
      // $('.QltyParamDiv').hide();
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
            vehicleDetail();
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
        $('.QltyParamDiv').show();
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
    function vehicleDetail(){
            var url = '" . Url::to(['/transporter/tbl-vehicle-km-info/route-vehicle-detail') . "'+ '?' + $('#gate-entry-form').serialize();
                $.ajax({
                    type: 'get',
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                    console.log(data);

                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
";
$this->registerJs($script, View::POS_END, 'gate-entry-create');
?>
