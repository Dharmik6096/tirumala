<?php

namespace app\modules\staffmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staffmanagement\models\TblStaffAttendance;

/**
 * TblStaffAttendanceSearch represents the model behind the search form about `app\modules\staffmanagement\models\TblStaffAttendance`.
 */
class TblStaffAttendanceSearch extends TblStaffAttendance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'is_active', 'is_delete', 'lwp_type', 'salary_processed'], 'integer'],
            [['staff_attendance_id','created_at', 'deleted_at','staff_member_name', 'flg_sentbox_entry', 'lwp_date', 'remark', 'sync_status', 'sync_timestamp', 'updated_at', 'created_by', 'deleted_by', 'staff_member_code', 'updated_by', 'dcs_code', 'sub_center_code', 'union_code'], 'safe'],
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
        $query = TblStaffAttendance::find();

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
            'staff_attendance_id' => $this->staff_attendance_id,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'tbl_staff_attendance.is_active' => $this->is_active,
            'lwp_date' => $this->lwp_date,
            'lwp_type' => $this->lwp_type,
            'salary_processed' => $this->salary_processed,
            'sync_timestamp' => $this->sync_timestamp,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'flg_sentbox_entry', $this->flg_sentbox_entry])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'sync_status', $this->sync_status])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'tbl_staff_member.staff_member_name', $this->staff_member_name])
            ->andFilterWhere(['like', 'tbl_staff_attendance.staff_member_code', $this->staff_member_code])
            ->andFilterWhere(['like', 'tbl_dcs.dcs_code', $this->dcs_code])
            ->orFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
            ->andFilterWhere(['like', 'sub_center_code', $this->sub_center_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }
}
