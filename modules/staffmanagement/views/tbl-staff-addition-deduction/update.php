<?php
$this->title = Yii::$app->label->title('edit', 'Staff Addition Deduction');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="MainForm">
            <?=
            $this->render('salary_install_form', [
                'model' => $model,
                'type' => 'edit',
                'staffModel' => $staffModel
            ])
            ?>
        </div>
        <div class="clearfix"></div>
        <div class="InstallationDetail">
            <?=
            $this->render('salary_install_detail_grid', [
                'model' => $model,
                'type' => 'edit',
                'staffModelData' => $staffModelData,
            ])
            ?>
        </div>

    </div>
</div>
