<?php

namespace app\modules\tms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tms\models\TblUserAttendance;
use app\modules\usermanagement\models\User;

/**
 * TblUserAttendanceSearch represents the model behind the search form about `app\modules\tms\models\TblUserAttendance`.
 */
class TblUserAttendanceSearch extends TblUserAttendance
{
    public $from_date, $to_date, $department_wise = 1;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attendance_code', 'originating_type'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'user_code', 'attendance_date', 'in_time', 'out_time', 'in_lat_long', 'out_lat_long', 'in_desc', 'out_desc', 'status', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'department_wise'], 'safe'],
            [['day_count'], 'number'],
            [['from_date', 'to_date', 'duration', 'api_status', 'pick_datetime', 'response_datetime', 'response_msg', 'state_code', 'region_code', 'area_code'], 'safe']
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
        $query = TblUserAttendance::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode', 'userCode', 'areaBmcMapping','areaBmcMapping.mainAreaCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_user_attendance', 'tbl_user_attendance', 'tbl_user_attendance');
        
        $from_date = (!empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d')) . ' 00:00:00';
        $to_date = (!empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d')) . ' 00:00:00';

        $query->andFilterWhere(['>=', 'tbl_user_attendance.attendance_date', $from_date]);
        $query->andFilterWhere(['<=', 'tbl_user_attendance.attendance_date', $to_date]);

        $query->andFilterWhere([
            'tbl_user_attendance.attendance_date' => $this->attendance_date,
        ]);
        if($this->department_wise != 0) {
            $department = User::find()->select('department')->where(['id' => Yii::$app->session->get('UserCode')])->scalar();
            !empty($department) && $query->andWhere(['user.department' => $department]);
        }
        $query->andFilterWhere(['like', 'tbl_unions.union_name', $this->union_code])
            ->andFilterWhere(['like', 'tbl_plant.name', $this->plant_code])
            ->andFilterWhere(['like', 'tbl_mcc_plant.name', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code])
            ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
            ->andFilterWhere(['like', '[user].name', $this->user_code])
            ->andFilterWhere(['like', 'tbl_user_attendance.status', $this->status])
            ->andFilterWhere(['like', 'tbl_user_attendance.api_status', $this->api_status])
            ->andFilterWhere(['tbl_area.state_code' => $this->state_code])
            ->andFilterWhere(['tbl_area.region_code' => $this->region_code])
            ->andFilterWhere(['tbl_area.area_code' => $this->area_code]);
        return $dataProvider;
    }
}
