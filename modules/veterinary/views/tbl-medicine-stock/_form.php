<div id="maincontent">
    <?=
    $this->render('medicine_transfer_form', ['model' => $model, 'type' => 'create', 'txModel' => $txModel, 'batchNoWiseInventory' => $batchNoWiseInventory,])
    ?>
</div>
<div class="col-md-12 padding_10_0 theme-box view-subtitle">
    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
        <h4 class="theme-box-heading"><?= Yii::t('app', 'Medicine Stock Transactions') ?></h4>
    </div>
    <div class="form-grid">
        <?=
        $this->render('_transaction_detail', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'isVisible' => true,
            'txnEdit' => 'false',
        ]);
        ?>
    </div>
</div>