<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblUnions;

/**
 * TblUnionsSearch represents the model behind the search form about `app\modules\organisation\models\TblUnions`.
 */
class TblUnionsSearch extends TblUnions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code', 'address','fax_no', 'upi_no','bank_account_no', 'city', 'contact_person', 'contact_person_email', 'contact_person_mobile_no', 'contact_person_pan_no', 'contact_person_phone_no', 'created_at', 'ifsc', 'phone_no', 'pincode', 'registration_date', 'registration_no', 'union_code_ex', 'union_name', 'updated_at', 'bank_code', 'branch_code', 'created_by','district_code', 'federation_code', 'hamlet_code', 'state_code', 'sub_district_code', 'updated_by', 'village_code', 'valid_from'], 'safe'],
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
       $query = TblUnions::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['union_name'=>SORT_ASC]],
        ]);

        $this->load($params);

        if(Yii::$app->session->get('Unions')!==''){
            $query->andFilterWhere([ 'tbl_unions.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }
        
//        $unions = Yii::$app->session->get('Unions');
//        if ($unions !== null) {
//            $unionCodes = explode(',', $unions);
//            if (!empty($unionCodes)) {
//                $query->andFilterWhere(['tbl_unions.union_code' => $unionCodes]);
//            }
//        }

        $query->joinWith(['stateCode']);

        if (!$this->validate()) {

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_unions.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_unions.union_code', $this->union_code])
            ->andFilterWhere(['like', 'contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'union_code_ex', $this->union_code_ex])
            ->andFilterWhere(['like', 'union_name', $this->union_name])
            ->andFilterWhere(['like', 'upi_no', $this->upi_no])
            ->andFilterWhere(['like', 'tbl_states.state_name', $this->state_code]);

        return $dataProvider;
    }
}
