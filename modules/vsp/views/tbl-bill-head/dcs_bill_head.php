<?php
$this->title = Yii::t('app', 'DCS Wise Bill Head Applicability');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php echo $this->render('_search_bill_head', ['model' => $model]); ?>
        <div class="clearfix"></div>
        <?=
        $this->render('_bill_head_grid', ['model' => $model, 'dcs' => $dcs, 'head' => $head]);
        ?>

    </div>
</div>