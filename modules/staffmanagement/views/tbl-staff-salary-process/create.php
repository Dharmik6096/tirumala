<?php

use yii\helpers\Url;
use yii\web\View;
?>
<?php
$this->title = Yii::t('app', 'Staff Salary Process');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <div id="maincontent">
            <?=
            $this->render('_search', ['model' => $model, 'type' => 'create',])
            ?>
        </div>
        <div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
            <?=
            $this->render('_form', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'model' => $model])
            ?>
        </div>
    </div>
</div>


<?php
$script = "
    function setNetPay(id, parent){
        var adjust = parseFloat(parent.find('.adjust-amount').val());
        var final = parseFloat(parent.find('.final-amount').text());
        var hold = parseFloat(parent.find('.hold-amount').val());
        var prehold = parseFloat(parent.find('.previous-hold').text());
        var predue = parseFloat(parent.find('.previous-due').text());
  
        if(adjust == '' ||  isNaN(adjust)){
            adjust=0;
        }
        if(hold == '' ||  isNaN(hold)){
            hold=0;
        }
        if(prehold == '' ||  isNaN(prehold)){
            prehold=0;
        }
        if(predue == '' ||  isNaN(predue)){
            predue=0;
        }
        var total= final - predue + prehold;
        var net = total + adjust - hold; 
       
        parent.find('.net-amount').val(net.toFixed(2));
        if(net != '' && net < 0){
        bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>Net Payable should not be less than 0.</span>',function(){
                bootbox.hideAll();
                    $('#'+id).focus().select();
                    $('#'+id).val('');
                    parent.find('.net-amount').val(total);
            });
            return false;
        } 
    }

    $(document).on('change','.cal-amount' ,function(){  
        var id = $(this).attr('id');
        var parent = $(this).parents('tr');
        setNetPay(id, parent);
    });

    function reloadGrid(){
            var url = '" . Url::to(['/staffmanagement/tbl-staff-salary-process/process-grid']) . "'+ '?' + $('#staff-member-form').serialize();
                $.ajax({
                    type: 'get',
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#gridcontentSet').html(data);
                        $( '.cal-amount' ).each(function( index ) {
                                var id = $(this).attr('id');
                                var parent = $(this).parents('tr');
                                setNetPay(id, parent);
                        });
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
     var specialDecimalKeys = new Array();
        specialDecimalKeys.push(8);
        $(document).on('keypress', '.number-validate', function (e) {
            var keyCode = e.which ? e.which : e.keyCode
            var ret = ((keyCode >= 48 && keyCode <= 57) || (specialDecimalKeys.indexOf(keyCode) != -1) || keyCode == 9 || keyCode == 46);
            return ret;
        });
   
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>