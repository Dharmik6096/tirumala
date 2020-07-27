<?php
$this->title = Yii::$app->label->title('create', 'Payment Configurations');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="grid-search search-filter padding_left_0 padding_right_0 searchBtnReport text-right beforeGridLoad">
            <div class="btn-group btn btn-default mis_report_modal_toggle"><i class="fa fa-search"></i></div>
        </div>
        <?=
        $this->render('payment_form', [
            'model' => $model,
            'type' => 'create',
        ])
        ?>
    </div>
</div>