<?php

use yii\helpers\Html;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\widgets\Pjax;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use kartik\grid\GridView;

$this->title = Yii::$app->label->title('create', 'Indent Master');
?>
<?php
$this->title = Yii::$app->label->title('create', 'Indent Master');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <div id="maincontent">
            <?=
            $this->render('_form', ['model' => $model, 'type' => 'create',])
            ?>
        </div>

        <div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
            <div class="QltyParamDivGrid">
                <?=
                $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider])
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$script = "
    gridChange();
    $(document).on('change', '#tblindentmaster-plant_code', function() {  
        gridChange();
    });
    $(document).on('change', '#tblindentmaster-mcc_plant_code', function() {  
       gridChange();
    });
    $(document).on('change', '#tblindentmaster-bmc_code', function() { 
         gridChange();
    });
    $(document).on('change', '#tblindentmaster-indent_date', function() {  
        gridChange();
    });
    
    function gridChange(){
       $('.add-collection').prop('disabled',true);
       $('#indent-master-from .reset_field input').val('');
       $('.QltyParamDiv').hide();
        var plant = $('#tblindentmaster-plant_code').val();
        var bmc = $('#tblindentmaster-mcc_plant_code').val();
        var mcc = $('#tblindentmaster-bmc_code').val();
        var date = $('#tblindentmaster-indent_date').val();
        if(setData(plant) && setData(mcc) && setData(bmc) && setData(date)){
            $('.add-collection').removeAttr('disabled');
        } 
        
    }
    function setData(field = ''){
        if(field != '' && field != null && field != undefined && field != 'Loading ...'){
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
            var url = '" . Url::to(['/product/tbl-indent-master/list-grid']) . "'+ '?' + $('#indent-master-from').serialize();
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
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>