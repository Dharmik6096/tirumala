<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblFederations;

/**
 * TblFederationsSearch represents the model behind the search form about `app\modules\organisation\models\TblFederations`.
 */
class TblFederationsSearch extends TblFederations
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['federation_code', 'address','fax_no', 'bank_account_no', 'city', 'contact_person', 'contact_person_email', 'contact_person_mobile_no', 'contact_person_pan_no', 'contact_person_phone_no', 'created_at', 'federation_code_ex', 'federation_name','ifsc', 'phone_no', 'pincode', 'registration_date', 'registration_no', 'updated_at', 'bank_code', 'branch_code', 'created_by', 'district_code', 'hamlet_code', 'state_code', 'sub_district_code', 'updated_by', 'village_code'], 'safe'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblFederations::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['federation_name'=>SORT_ASC]],
        ]);

        $query->joinWith(['stateCode']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if(Yii::$app->session->get('Federations')!==''){
            $query->andFilterWhere([ 'tbl_federations.federation_code'=>explode(',',Yii::$app->session->get('Federations'))]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_federations.is_active' => $this->is_active,
        ]);

        if(!empty($this->registration_date))
            $query->andFilterWhere(['like', 'tbl_federations.registration_date', date('Y-m-d', strtotime($this->registration_date))]);

        $query->andFilterWhere(['like', 'tbl_federations.federation_code', $this->federation_code])
            ->andFilterWhere(['like', 'contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'contact_person_mobile_no', $this->contact_person_mobile_no])
            ->andFilterWhere(['like', 'federation_code_ex', $this->federation_code_ex])
            ->andFilterWhere(['like', 'federation_name', $this->federation_name])
            ->andFilterWhere(['like', 'tbl_states.state_name', $this->state_code]);


        return $dataProvider;
    }
}
