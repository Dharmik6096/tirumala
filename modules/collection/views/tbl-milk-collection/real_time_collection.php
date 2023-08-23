<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?> Online MCC Collection Live      
        </div>
        <div class="panel-body" id="collection_live_details">
            <?php
            //echo $this->render('_real_time_collection_details')
            ?>
        </div>
    </div>
</div>
<?php
$script = "
    $(document).ready(function() {
        getCollectionLiveData()
        setInterval(function () { getCollectionLiveData() }, 15000);
    });
    
    function getCollectionLiveData() {
        $.ajax({
            type: 'post',
            url:'" . Url::to(['real-time-collection']) . "',
            beforeSend:function(data) {
//                $('#loadercontent').show();
//                $('#pageloader').show();
            },
            success: function(data) {
                $('#collection_live_details').html(data);           
//                $('#loadercontent').hide();
//                $('#pageloader').hide();                                                                  
            },
            error: function(data) {  
//                $('#loadercontent').hide();
//                $('#pageloader').hide();
            }
        });
    }
";
$this->registerJs($script, View::POS_END, 'mcc-collection-online');
?>