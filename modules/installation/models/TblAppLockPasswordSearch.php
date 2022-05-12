<?php

namespace app\modules\installation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\installation\models\TblAppLockPassword;

/**
 * TblAppLockPasswordSearch represents the model behind the search form about `app\modules\installation\models\TblAppLockPassword`.
 */
class TblAppLockPasswordSearch extends TblAppLockPassword {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['app_lock_password_code', 'android_key', 'hour', 'app_password', 'dcs_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblAppLockPassword::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_app_lock_password', 'tbl_app_lock_password', 'tbl_app_lock_password', 'tbl_app_lock_password');
        // grid filtering conditions
        if (!empty($this->created_at))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), created_at, 126)', date('Y-m-d', strtotime($this->created_at))]);

        $query->andFilterWhere(['like', 'app_lock_password_code', $this->app_lock_password_code])
                ->andFilterWhere(['like', 'android_key', $this->android_key])
                ->andFilterWhere(['like', 'hour', $this->hour])
                ->andFilterWhere(['like', 'app_password', $this->app_password]);

        return $dataProvider;
    }

}
