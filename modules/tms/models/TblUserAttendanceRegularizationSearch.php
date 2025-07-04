<?php

namespace app\modules\tms\models;

use app\models\TblUserOrganizationMapping;
use app\modules\general\models\TblProcessApproval;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tms\models\TblUserAttendanceRegularization;
use app\modules\usermanagement\models\User;
use yii\db\Expression;

/**
 * TblUserAttendanceRegularizationSearch represents the model behind the search form about `app\modules\tms\models\TblUserAttendanceRegularization`.
 */
class TblUserAttendanceRegularizationSearch extends TblUserAttendanceRegularization {

    public $mcc_user_code, $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['regularization_code', 'originating_type'], 'integer'],
            [['attendance_code', 'user_code', 'apply_date', 'regularization_reason', 'status', 'requested_in_time', 'requested_out_time', 'approved_by', 'approved_date', 'rejected_by', 'rejected_date', 'rejection_remark', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'mcc_user_code', 'from_date', 'to_date', 'f_union_code', 'f_plant_code', 'f_mcc_code'], 'safe'],
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
    public function search($params, $pending_approval = false) {
        $query = TblUserAttendanceRegularization::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['userCode as user', 'approvedUserCode as approvedUserCode']);

        if ($pending_approval) {
            $approval = new TblProcessApproval();
            $subQuery = $approval->getApproveLavel('tbl_user_attendance_regularization');
            $query->innerJoin(['ap' => $subQuery], 'convert(varchar(max),tbl_user_attendance_regularization.regularization_code) = convert(varchar(max),ap.process_code)');
            $query->addSelect(['tbl_user_attendance_regularization.*', 'ap.process_approval_code as process_approval_code']);
            $query->where(['tbl_user_attendance_regularization.status' => '0']);
        }

        if (!Yii::$app->user->isSuperadmin && Yii::$app->session->get('organizations_type') != 'FEDERATION') {
            $mccCodes = explode(',', Yii::$app->session->get('MCC'));
        
            $query1 = TblUserOrganizationMapping::find()
                ->alias('tuom')
                ->select(['tcd.mobile_no', 'tuom.user_id AS user_id', 'u.login_type', 'u.name AS user_name', new \yii\db\Expression("'1' AS order_by")])
                ->innerJoin('user u', 'tuom.user_id = u.id')
                ->innerJoin('tbl_contact_details tcd', 'tcd.module_code = u.id')
                ->where(['tuom.organization_type' => 'MCC'])
                ->andWhere(['tuom.organization_code' => $mccCodes]);

            $query2 = User::find()
                    ->alias('u')
                    ->select(['tcd.mobile_no', 'u.id AS user_id', new \yii\db\Expression("'route_supervisor' AS login_type"), 'u.name AS user_name', new \yii\db\Expression("'2' AS order_by")])
                    ->distinct()
                    ->innerJoin('tbl_contact_details tcd', 'tcd.module_code = u.id')
                    ->innerJoin('tbl_user_organization_mapping tuom', "tuom.user_id = u.id AND tuom.organization_type = 'DCS'")
                    ->innerJoin('tbl_dcs d', 'd.dcs_code = tuom.organization_code')
                    ->where(['d.mcc_plant_code' => $mccCodes]);

            $unionSql = (new \yii\db\Query())
                    ->select('*')
                    ->from(['unioned' => $query1->union($query2)])
                    ->orderBy(['order_by' => SORT_ASC]);

            $userData = $unionSql->all();

            $userIds = array_column($userData, 'user_id');
            $userIds[] = Yii::$app->session->get('UserCode');

            $query->andWhere(['tbl_user_attendance_regularization.user_code' => $userIds]);
        }


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'CAST(tbl_user_attendance_regularization.apply_date as date)', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'CAST(tbl_user_attendance_regularization.apply_date as date)', $to_date]);
        }

        if (!empty($this->apply_date)) {
            $apply_date = date('Y-m-d', strtotime($this->apply_date));
            $query->andFilterWhere(['=', new Expression('CAST(tbl_user_attendance_regularization.apply_date AS DATE)'), $apply_date]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_user_attendance_regularization.user_code' => $this->mcc_user_code,
        ]);

        $query->andFilterWhere(['like', 'attendance_code', $this->attendance_code])
                ->andFilterWhere(['like', 'user.name', $this->user_code])
                ->andFilterWhere(['like', 'approvedUserCode.name', $this->approved_by])
                ->orderBy('apply_date asc');
        
        return $dataProvider;
    }

}
