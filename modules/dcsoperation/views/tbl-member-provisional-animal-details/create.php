<?php
$this->title = Yii::$app->label->title('create', 'Member Provisional Details');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'animals' => $animals,
            'member_animal_model' => $member_animal_model,
            'member_animal_model_data' => $member_animal_model_data,
            'type' => 'create',
            'msearchModel' => $msearchModel,
            'mdataProvider' => $mdataProvider,
            'memberFamilyDetail' => $memberFamilyDetail,
            'memberFamilySearchModel' => $memberFamilySearchModel,
            'memberFamilyDataProvider' => $memberFamilyDataProvider,
            'memberShareDetail' => $memberShareDetail,
        ])
        ?>

    </div>
</div>