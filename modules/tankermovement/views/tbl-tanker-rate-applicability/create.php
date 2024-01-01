<?php
$this->title = Yii::$app->label->title('create', 'Mapping');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?> for Rate<?= $purchaseRate->description.'('.$purchaseRate->tanker_rate_code.')' ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
            'purchaseRate' => $purchaseRate,
            'party_list' => $party_list,
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