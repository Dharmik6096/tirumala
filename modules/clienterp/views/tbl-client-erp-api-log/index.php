<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Client Erp Api Logs'));
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <?php
            if($searchModel['erp_process_name'] == 1){
                echo $this->render('_form_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ]);   
            } else {
                echo $this->render('_milk_form_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ]);   
            }
            ?>
        </div>
    </div>
</div>