<?php
$this->title = Yii::$app->label->title('edit', 'Society');

use yii\web\View;
$this->title.= ' > ' . $model->dcs_code_ex . ' > ' . $model->ref_code;

?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model, 'type' => 'edit'//,'milkType'=>$milkType',village_list'=>$village_list,
        ])
        ?>
    </div>
</div>
<?php
$script = "
        $(document).ajaxStop(function() {
            $('.depend-control').each(function(){
                $(this).attr('disabled', 'disabled');
            })
        });";
Yii::$app->view->registerJs($script, View::POS_READY,'disable-dep');
?>