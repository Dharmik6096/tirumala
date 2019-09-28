<?php

namespace app\modules\details\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\details\models\TblContactDetails;

/**
 * TblContactDetailsSearch represents the model behind the search form about `app\modules\details\models\TblContactDetails`.
 */
class TblContactDetailsSearch extends TblContactDetails {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['detail_code'], 'integer'],
            [['module_name', 'module_code', 'contact_person', 'email', 'mobile_no', 'local_contact_person', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblContactDetails::find();

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


//                ->andFilterWhere(['like', 'module_name', $this->module_name])
//            ->andFilterWhere(['like', 'module_code', $this->module_code])
        $query->andFilterWhere(['like', 'contact_person', $this->contact_person])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'local_contact_person', $this->local_contact_person])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

}
