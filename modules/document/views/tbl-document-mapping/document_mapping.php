<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
    </div>
    <div class="panel-body">
        <div class=" large-search hidden-print">
            <?php echo $this->render('_search_mapping', ['model' => $model]); ?>
        </div>
        <div class="clearfix"></div>
        <?php
        echo $this->render('form_document_mapping', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'selectedArray' => $selectedArray, 'model' => $model, 'mandateselectedArray' => $mandateselectedArray]);
        ?>
    </div>
</div>
