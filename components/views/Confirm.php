<?php
use yii\web\View;
$script = "$(function() {
                    bootbox.confirm({
                        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-exclamation\'></i></div><span>".$msg."</span></div></div>',
                        buttons: {
                            'cancel': {
                                            label: 'No',
                                            className: 'btn btn-danger'
                              },
                            'confirm': {
                                            label: 'Yes',
                                            className: 'btn btn-primary'
                             }
                        },
                        callback: function(result) {
                            if (!result){
                                bootbox.hideAll();
                                $('#".$field."').focus().select();
                            }else{
                                $('#".$hiddenfield."').val(1);
                            }
                        },
                        closeButton: false,
                    });
        });";

$this->registerJs($script, View::POS_END,'warning'); ?>