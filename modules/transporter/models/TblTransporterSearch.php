<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblTransporter;

/**
 * TblTransporterSearch represents the model behind the search form about `app\modules\organisation\models\TblTransporter`.
 */
class TblTransporterSearch extends TblTransporter
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transporter_code', 'transporter_name', 'local_name', 'address', 'phone_no', 'mobile_no', 'email', 'pincode', 'registration_no', 'contact_person', 'local_contact_person', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'gstin', 'pan_no', 'beneficiary_name', 'agreement_no', 'declaration', 'security_cheque_no', 'union_code', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['tds_per'], 'number'],
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
        $query = TblTransporter::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['transporter_name'=>SORT_ASC]],
        ]);

        $this->load($params);
        if(Yii::$app->session->get('Unions')!=='' && empty($this->union_code)){
            $query->andFilterWhere([ 'tbl_transporter.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }
        else{
            $query->andFilterWhere(['tbl_transporter.union_code'=> $this->union_code]);
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tds_per' => $this->tds_per,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'transporter_code', $this->transporter_code])
            ->andFilterWhere(['like', 'transporter_name', $this->transporter_name])
            ->andFilterWhere(['like', 'local_name', $this->local_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone_no', $this->phone_no])
            ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'pincode', $this->pincode])
            ->andFilterWhere(['like', 'registration_no', $this->registration_no])
            ->andFilterWhere(['like', 'contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'local_contact_person', $this->local_contact_person])
            ->andFilterWhere(['like', 'bank_code', $this->bank_code])
            ->andFilterWhere(['like', 'branch_code', $this->branch_code])
            ->andFilterWhere(['like', 'bank_account_no', $this->bank_account_no])
            ->andFilterWhere(['like', 'ifsc', $this->ifsc])
            ->andFilterWhere(['like', 'gstin', $this->gstin])
            ->andFilterWhere(['like', 'pan_no', $this->pan_no])
            ->andFilterWhere(['like', 'beneficiary_name', $this->beneficiary_name])
            ->andFilterWhere(['like', 'agreement_no', $this->agreement_no])
            ->andFilterWhere(['like', 'declaration', $this->declaration])
            ->andFilterWhere(['like', 'security_cheque_no', $this->security_cheque_no])
            //->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'state_code', $this->state_code])
            ->andFilterWhere(['like', 'district_code', $this->district_code])
            ->andFilterWhere(['like', 'sub_district_code', $this->sub_district_code])
            ->andFilterWhere(['like', 'village_code', $this->village_code])
            ->andFilterWhere(['like', 'hamlet_code', $this->hamlet_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
