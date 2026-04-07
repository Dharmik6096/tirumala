<?php

$this->title = Yii::$app->label->title('create', 'Member Provisional Family Details');
?>
<?=

$this->render('_form', [
    'msearchModel' => $msearchModel,
    'mdataProvider' => $mdataProvider,
    'memberFamilyDetail' => $memberFamilyDetail,
    'memberFamilySearchModel' => $memberFamilySearchModel,
    'memberFamilyDataProvider' => $memberFamilyDataProvider,
    'model' => $model,
    'tabview' => !empty($tabview) ? $tabview : false
])
?>
