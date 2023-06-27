<?php
use yii\web\View;
if($type=='successbar'){
    $class='fa fa-check';
    $bg = 'bg-succ';
}
else{
    $class='fa fa-times';
    $bg = 'bg-danger';
}

$script = '$(function() {
            var msg = "'.$msg.'";
            var css_class="'.$type.'";
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\''.$bg.'\'><i class=\''.$class.'\'></i></div><span>'.stripslashes($msg).'</span></div></div>");
            //$.snackbar({content: msg, timeout: 8000, style: css_class});
        });';
$this->registerJs($script, View::POS_END,'snackbarAlert');

?>