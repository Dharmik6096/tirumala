<?php

namespace app\modules\installation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\installation\models\TblUserAndroid;

/**
 * TblUserAndroidSearch represents the model behind the search form about `app\modules\installation\models\TblUserAndroid`.
 */
class TblUserAndroidSearch extends TblUserAndroid {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_code', 'name', 'username', 'password', 'mobile_no', 'email', 'device_id', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'role_code', 'is_active'], 'safe'],
            [['originating_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = TblUserAndroid::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['userCode', 'userCode.roleCode']);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        Yii::$app->general->filterByOrg($query, $this, 'tbl_user_android', 'tbl_user_android', 'tbl_user_android');
        $query->andFilterWhere(['like', 'user_code', $this->user_code])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'password', $this->password])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'device_id', $this->device_id])
                ->andFilterWhere(['like', 'is_active', $this->is_active])
                ->andFilterWhere(['like', 'tbl_role.description', $this->role_code]);


        return $dataProvider;
    }

}
