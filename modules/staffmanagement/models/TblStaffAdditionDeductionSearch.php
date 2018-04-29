<?php

namespace app\modules\staffmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staffmanagement\models\TblStaffAdditionDeduction;

/**
 * TblStaffAdditionDeductionSearch represents the model behind the search form about `app\modules\staffmanagement\models\TblStaffAdditionDeduction`.
 */
class TblStaffAdditionDeductionSearch extends TblStaffAdditionDeduction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tr_no', 'installment_no', 'is_active', 'is_delete', 'type'], 'integer'],
            [['amount'], 'number'],
            [['app_from_date', 'staff_member_name', 'created_at', 'deleted_at', 'flg_sentbox_entry', 'remark', 'sync_status', 'sync_timestamp', 'tr_date', 'updated_at', 'created_by', 'dcs_code', 'deleted_by', 'staff_member_code', 'sub_center_code', 'union_code', 'updated_by'], 'safe'],
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
        $query = TblStaffAdditionDeduction::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['staffMemberCode','dcsCode']);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tr_no' => $this->tr_no,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'installment_no' => $this->installment_no,
            'tbl_staff_addition_deduction.is_active' => $this->is_active,
            'tbl_staff_addition_deduction.is_delete' => $this->is_delete,
            'sync_timestamp' => $this->sync_timestamp,
            'tr_date' => $this->tr_date,
            'type' => $this->type,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'app_from_date', $this->app_from_date])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'tbl_dcs.dcs_code', $this->dcs_code])
            ->orFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
            ->andFilterWhere(['like', 'tbl_staff_addition_deduction.staff_member_code', $this->staff_member_code])
            ->andFilterWhere(['like', 'tbl_staff_member.staff_member_name', $this->staff_member_name])
            ->andFilterWhere(['like', 'sub_center_code', $this->sub_center_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }
}
