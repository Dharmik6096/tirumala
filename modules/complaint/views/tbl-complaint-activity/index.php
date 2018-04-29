<?php

use yii\widgets\ListView;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Complaint Activities'));
?>

<div class="list-group">
    <?=
    ListView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>'{items}',
        'itemView' => '_complaint_activity',
    ]);
    ?>
</div>
