<?php

use yii\helpers\Html;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\grid\GridView;

$this->title = Yii::$app->label->title('create', 'Approval Stages Master');
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <div id="maincontent">
            <?=
            $this->render('_form', ['model' => $model, 'txModel' => $txModel, 'type' => 'edit',])
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
    $('.user_dd').hide();
    $('.login_type_dd').hide();
    gridChange();
    $(document).on('change', '#tblapprovalstages-union_code', function() {  
        gridChange();
    });
    $(document).on('change', '#tblapprovalstages-process_name', function() {  
        gridChange();
    });
    $(document).on('change', '#tblapprovalstages-approval_mode', function() {  
       gridChange();
    });
    
    $(document).on('change', '#tblapprovalstagesdetail-approval_type', function() {  
       hideShowFields();
    });
    
    function hideShowFields(){
        var type = $('#tblapprovalstagesdetail-approval_type').val();
        if(type == '1'){
            $('.user_dd').show();
            $('.login_type_dd').hide();
            $('#tblapprovalstagesdetail-login_type').val('');
            $('#tblapprovalstagesdetail-login_type').trigger('change');
            $('#tblapprovalstagesdetail-login_type').trigger('select2:select');
        }else if(type == '2'){
            $('.user_dd').hide();
            $('.login_type_dd').show();
            $('#tblapprovalstagesdetail-user_code').val('');
            $('#tblapprovalstagesdetail-user_code').trigger('change');
            $('#tblapprovalstagesdetail-user_code').trigger('select2:select');
        }
    }
    
    function gridChange(){
        $('.add-collection').prop('disabled',true);
        $('#approval-master-from .reset_field input').val('');
        $('.QltyParamDiv').show();
        $('.DisableAferAdd').addClass('disabledDiv'); 
       
        
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
            var url = '" . Url::to(['/general/tbl-approval-stages/list-grid']) . "'+ '?' + $('#approval-master-from').serialize();
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