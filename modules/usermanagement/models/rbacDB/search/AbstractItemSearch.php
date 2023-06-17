<?php

namespace app\modules\usermanagement\models\rbacDB\search;

use Yii;
use webvimark\modules\UserManagement\models\rbacDB\Permission;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use yii\data\ActiveDataProvider;

class AbstractItemSearch extends \webvimark\modules\UserManagement\models\rbacDB\search\AbstractItemSearch {

    public function search($params) {
        $query = ( static::ITEM_TYPE == static::TYPE_ROLE ) ? Role::find() : Permission::find();

        $query->joinWith(['group']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
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
