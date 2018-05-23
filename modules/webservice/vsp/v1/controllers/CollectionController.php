<?php

namespace app\modules\webservice\vsp\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\webservice\vsp\v1\models\Collection;
use Yii;

class CollectionController extends ChildController {

    public function actionCollectionData() {
        $model = new Collection();
        $model->setAttributes($this->post_data);
        $content = $this->post_data['content'];
        return $this->response['data'] = $model->collectionData($content['shift'], $content['date']);
    }
    
    public function actionMemberCollectionData() {
        $model = new Collection();
        $model->setAttributes($this->post_data);
        $content = $this->post_data['content'];
        return $this->response['data'] = $model->memberCollectionData($content['member_code'], $content['from_date'], $content['to_date']);
    }
    
}
