<?php

namespace app\modules\usermanagement\models\rbacDB\search;

use webvimark\modules\UserManagement\models\rbacDB\AuthItemGroup;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AuthItemGroupSearch extends \webvimark\modules\UserManagement\models\rbacDB\search\AuthItemGroupSearch {

    public function search($params) {
        $query = AuthItemGroup::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->created_at) {
            $tmp = explode(' - ', $this->created_at);
            if (isset($tmp[0], $tmp[1])) {
                $query->andFilterWhere(['between', Yii::$app->getModule('user-management')->auth_item_group_table . '.created_at', strtotime($tmp[0]), strtotime($tmp[1])]);
            }
        }

        $query->andFilterWhere(['like', Yii::$app->getModule('user-management')->auth_item_group_table . '.code', $this->code])
                ->andFilterWhere(['like', Yii::$app->getModule('user-management')->auth_item_group_table . '.name', $this->name]);

        return $dataProvider;
    }

}
