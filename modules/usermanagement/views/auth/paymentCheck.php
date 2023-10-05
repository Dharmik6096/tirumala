
<?php

use yii\web\View;

$message = '';
if (Yii::$app->session->hasFlash('success')) {
    $msg = Yii::$app->session->getFlash('success');
    if (isset($msg['type']) && $msg['type'] == 'paymentErr') {
        $message = $msg['message'];
//        $type = isset($msg['type']) ? $msg['type'] : 'success';
//        $field = isset($msg['field']) ? $msg['field'] : '';
//        $hiddenfield = isset($msg['hidden_field']) ? $msg['hidden_field'] : '';
//        $alertType = $type == 'success' ? 'successbar' : 'errorbar';
//        Yii::$app->display->show($msg['message'], $alertType, $type, $field, $hiddenfield);
    }
}
?>
<div class="modal fade" id="paymentErr" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="col-sm-12 loginError">
                    <div class="bg-danger text-center">
                        <i class="fa fa-times"></i>
                    </div>

                </div>
            </div>
            <div class="modal-body">
                <span><?= $message ?></span>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">OK</button>
            </div>
        </div>
    </div>
</div>
<?php
if (!empty($message)) {
    $scriptOther = "
        $('#paymentErr').modal('show');";
    $this->registerJs($scriptOther, View::POS_READY, 'payment-err-login');
    Yii::$app->getSession()->removeFlash('success');
}
$css = <<<CSS
        .loginError i {
            font-size: 25px;
            height: 30px;
            width: 30px;
            border: 2px solid #fff;
            color: #fff;
            /* line-height: 46px; */
            border-radius: 50%
        }
CSS;

$this->registerCss($css);
?>