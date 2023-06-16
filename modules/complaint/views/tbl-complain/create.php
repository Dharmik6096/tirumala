<?php
$this->title = Yii::$app->label->title('create', 'Complain');
$type = !empty($type) ? $type : 'create'; //update_complaint
//$dependType = !empty($complaint_act_model) ? 'tblcomplaintactivity' : 'tblcomplaint';
//$complaint_act_model = !empty($complaint_act_model) ? $complaint_act_model : $model;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => $type,
//            'complaint_act_model' => $complaint_act_model,
//            'dependType' => $dependType
        ])
        ?>
    </div>
</div>
