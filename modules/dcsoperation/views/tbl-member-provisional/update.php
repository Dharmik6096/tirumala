<?php
$this->title = Yii::$app->label->title('edit', 'Provisional Members');

use yii\web\View;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">

        <?php
        if (Yii::$app->general->getUnionConfiguration($model->union_code, 'workflow_require', 'PORTAL') == 0) {
            echo
            $this->render('_form', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'type' => 'edit',
            ]);
        } else {
            echo $this->render('_form', [
                'model' => $model,
                'type' => 'edit',
            ]);
        }
        ?>
    </div>
</div>
