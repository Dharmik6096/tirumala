<?php

namespace app\modules\details\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\details\models\TblBankDetails;

/**
 * TblBankDetailsSearch represents the model behind the search form about `app\modules\details\models\TblBankDetails`.
 */
class TblBankDetailsSearch extends TblBankDetails {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['detail_code'], 'integer'],
            [['module_name', 'module_code', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblBankDetails::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['is_active' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'detail_code' => $this->detail_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'module_code' => $this->module_code,
            'module_name' => $this->module_name,
        ]);

//        ->andFilterWhere(['like', 'module_name', $this->module_name])
//                ->andFilterWhere(['like', 'module_code', $this->module_code])
        $query->andFilterWhere(['like', 'bank_code', $this->bank_code])
                ->andFilterWhere(['like', 'branch_code', $this->branch_code])
                ->andFilterWhere(['like', 'bank_account_no', $this->bank_account_no])
                ->andFilterWhere(['like', 'ifsc', $this->ifsc])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

}
