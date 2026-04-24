<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 */

use yii\web\View;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php

$script = " 

$(document).ready(function(){

    $(document).on('click','.view_history',function(e){
//    console.log('tset');
        $('#pageloader').show();
        $('#loadercontent').show();
        var table= $(this).attr('data-table_name');
        var id= $(this).attr('data-id');
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/misreports/reports/view-history']) . "',
            data:{'table':table, 'id':id},
            success: function(data) {   console.log(data);  
                $('#viewHistoryPopup').html(data);
                $('#viewHistoryPopupModal').modal('toggle'); 
                $(window).resize();
                $('#loadercontent').hide();
                $('#pageloader').hide();
            },    
            error: function(data) {    
            alert('asdasd');
                $('#loadercontent').hide();
                $('#pageloader').hide();
            }
        });
    });
    
    $(document).on('click', '#" . $id . " .delete-record', function(e) {
        var id= $(this).attr('data-val');
        var name = $(this).attr('data-name');
        bootbox.confirm({
            message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to delete \"'+name+'\"?</span></div></div>',
            buttons: {
                'cancel': {
                                label: 'Cancel',
                                className: 'btn btn-danger'
                  },
                'confirm': {
                                label: 'Ok',
                                className: 'btn btn-primary'
                 }
            },
            callback: function(result) {
                if (result) {
                  $('#loader').show();
                     $.ajax({
                            type: 'post',
                            url: '" . Url::to([$url]) . "',
                            data: 'id='+id,
                            success: function(data) {

                                var obj1 = $.parseJSON(data);
                                if (obj1.status == 'success')
                                {
                                    $.pjax.reload({container: '#" . $id . "'});
                                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                    //$.snackbar({content: 'Record successfully deleted.', timeout: 8000, style: 'successbar'});
                                }
                                else if (obj1.status == 'error'){
                                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                    //$.snackbar({content: 'Record is not deleted.', timeout: 8000, style: 'errorbar'});
                                }
                            },
                            error:function(data){
                                        //alert('Your data has not been submitted..Please try again');
                                    }
                });
                }
            }
        });
    });

    $(document).on('click', '.generalGridConfirmationPopup', function(){
        var postUrl = $(this).attr('data-post-url');
        var popupMessage = $(this).attr('data-popup-message');
        bootbox.confirm({
            message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>'+popupMessage+'?</span></div></div>',
            buttons: {
                'cancel': {
                                label: 'Cancel',
                                className: 'btn btn-danger'
                  },
                'confirm': {
                                label: 'Ok',
                                className: 'btn btn-primary'
                 }
            },
            callback: function(result) {
                if (result) {
                  $('#loader').show();
                     $.ajax({
                            type: 'get',
                            url: postUrl,
                            success: function(data) {

                                var obj1 = $.parseJSON(data);
                                if (obj1.status == 'success')
                                {
                                    $.pjax.reload({container: '#" . $id . "'});
                                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                    //$.snackbar({content: 'Record successfully deleted.', timeout: 8000, style: 'successbar'});
                                }
                                else if (obj1.status == 'error'){
                                    bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                    //$.snackbar({content: 'Record is not deleted.', timeout: 8000, style: 'errorbar'});
                                }
                            },
                            error:function(data){
                                        //alert('Your data has not been submitted..Please try again');
                                    }
                });
                }
            }
        });
    })
});";
$this->registerJs($script, View::POS_END, 'delete-manager');
?>
