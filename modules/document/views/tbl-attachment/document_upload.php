<?php
$this->title = Yii::$app->label->title('edit', 'Document Upload');

use yii\web\View;
?>
<div class="panel panel-default panel-main">
    <div class="panel-body  hide-grid-settings">
        <?=
        $this->render('_document_upload', [
            'model' => $model,
            'doc_model' => $doc_model,
            'type' => 'edit',
            'master_type' => $master_type,
        ])
        ?>
        <?=
        $this->render('_attachment_grid', [
            'dataProvider' => $dataProvider,
            'attachment' => $attachment,
        ])
        ?>
    </div>
</div>
