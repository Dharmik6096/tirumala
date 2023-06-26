<?php

use yii\helpers\Html;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\widgets\Pjax;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('edit', 'DPU Incentive Master');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <div id="maincontent">
            <?=
            $this->render('_form', ['model' => $model, 'detailModel' => $detailModel, 'type' => 'edit',])
            ?>
        </div>
        <div id="gridcontentSet" class='hide-grid-settings'>
            <?=
            $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider])
            ?>
        </div>
    </div>
</div>

<?php
$script = "
    $(document).on('change', '#tblcollectionincentivededuction-from_date', function() {  
      reloadGrid();
    });
    $(document).on('change', '#tblcollectionincentivededuction-to_date', function() {  
      reloadGrid();
    });
    function reloadGrid(){
            var url = '" . Url::to(['/general/tbl-dpu-incentive-master/list-grid']) . "'+ '?' + $('#dpu-incentive-form').serialize();
                $.ajax({
                    type: 'get',
                    url: url,
                    beforeSend:function(data) {
                        $('#loadercontent').show();
                        $('#pageloader').show();
                    },
                    success: function(data) {
                        $('#gridcontentSet').html(data);
                        $('#loadercontent').hide();
                        $('#pageloader').hide();
                    },
                });
    }
   
    $(document).ready(function(){
        $(document).on('click','.edit-record',function(e){
            var id= $(this).attr('data-val');
            var name = $(this).attr('data-name');
            editIncentiveDeduction(id);
        });
        $(document).on('click','.delete-detail-record',function(e){
            var id= $(this).attr('data-val');
            var name = $(this).attr('data-name');
            deleteRouteDetail(id);
        });

        function editIncentiveDeduction(incentive_deduction_id){
            if(incentive_deduction_id != ''){         
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/general/tbl-dpu-incentive-master/update-detail']) . "',
                    data: {'incentive_deduction_id' : incentive_deduction_id},
                    beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                    },
                    success: function(data) {
                        $.each(data.modelData, function(index, value) {
                            $('#tblcollectionincentivededuction-'+index).val(value);
                        });
//                         $('.create_fields').addClass('disabled');
                         $('#loadercontent').hide();
                         $('#pageloader').hide();
                         $(window).scrollTop(0);

                    },
                });
            }

    };
        function deleteRouteDetail(route_detail_code){
            if(route_detail_code != ''){         
                $.ajax({
                    type: 'post',
                    url: '" . Url::to(['/organisation/tbl-route-mapping/delete-detail']) . "',
                    data: {'id' : route_detail_code},
                    beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                    },
                    success: function(data) {
                      var data=$.parseJSON(data);               
                       bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+data.msg+'</span></div></div>');
                       $('#loadercontent').hide();
                       $('#pageloader').hide();
                       reloadGrid();
                        $(window).scrollTop(0);                                       
                    },
                });
            }
        }   
    });
  ";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>