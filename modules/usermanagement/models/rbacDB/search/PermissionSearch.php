<?php

namespace app\modules\usermanagement\models\rbacDB\search;

use app\modules\usermanagement\models\rbacDB\Permission;
use yii\data\ActiveDataProvider;
use yii;

class PermissionSearch extends \webvimark\modules\UserManagement\models\rbacDB\search\PermissionSearch {

    public function search($params) {
        $query = Permission::find();

        $query->joinWith(['group']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', Yii::$app->getModule('user-management')->auth_item_table . '.name', $this->name])
                ->andFilterWhere(['like', Yii::$app->getModule('user-management')->auth_item_table . '.description', $this->description])
                ->andFilterWhere([Yii::$app->getModule('user-management')->auth_item_table . '.group_code' => $this->group_code]);

        return $dataProvider;
    }

}
