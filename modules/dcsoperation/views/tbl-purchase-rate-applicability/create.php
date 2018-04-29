<?php
$this->title = Yii::$app->label->title('create', 'Society Mapping');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?> for <?= $purchaseRate->purchase_rate_code ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
            'purchaseRate' => $purchaseRate,
            'dcs_list' => $dcs_list,
            'selected' => $selected
        ])
        ?>
        <hr class="hr10">
        <div class="row">
            <div class="form-grid">
                <?=
                $this->render('_form_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ])
                ?>
            </div>
        </div>
    </div>
</div>