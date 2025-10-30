<?php

namespace app\modules\clienterp\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\clienterp\models\TblDataExchangeLog;
use Yii;
use app\modules\dcsoperation\models\TblMemberProvisional;
use app\modules\clienterp\models\TblDataExchangeLogHistory;

/**
 * TblDataExchangeLogSearch represents the model behind the search form about `app\modules\clienterp\models\TblDataExchangeLog`.
 */
class TblDataExchangeLogSearch extends TblDataExchangeLog {

    public $f_union_code, $f_plant_code, $f_mcc_plant_code, $f_bmc_code, $f_dcs_code, $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['f_union_code', 'f_plant_code', 'f_mcc_plant_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date', 'update_key', 'data_post_status', 'originating_type', 'process_name', 'process_code', 'picked_datetime', 'response_datetime', 'created_at', 'updated_at', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'resp_status', 'resp_desc', 'resp_msg', 'resp_param_1', 'resp_param_2', 'resp_param_3', 'resp_param_4', 'resp_param_5', 'resp_param_6'], 'safe'],
                [['f_mcc_plant_code', 'f_plant_code', 'f_union_code', 'process_name', 'from_date', 'to_date', 'data_post_status'], 'required', 'on' => ['dataExcahnge']],
                [['to_date'], 'validateToDate', 'on' => ['dataExcahnge']],
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
        $query = TblDataExchangeLog::find()->alias('del');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->leftJoin(['f' => 'tbl_member_provisional_family_details'], "CAST(f.member_provisional_family_detail_code AS NVARCHAR) = del.process_code");
        $query->leftJoin(['mp' => 'tbl_member_provisional'], "(del.process_name = 'Member Provisional' AND del.process_code = mp.provisional_member_code)
        OR (del.process_name = 'Member Provisional Family Detail' AND f.provisional_member_code = mp.provisional_member_code)");


        if (!empty($this->from_date) && !empty($this->to_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andWhere(['between', 'cast(mp.created_at as date)', $from_date, $to_date]);
        } else {
            $query->andWhere(['cast(mp.created_at as date)' => date('Y-m-d', strtotime('-2 days'))]);
        }

        Yii::$app->general->filterByOrg($query, $this, 'mp', 'mp', 'mp', 'mp');

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['=', new \yii\db\Expression('CAST(del.picked_datetime AS DATE)'), !empty($this->picked_datetime) ? date('Y-m-d', strtotime($this->picked_datetime)) : NULL]);
        $query->andFilterWhere(['=', new \yii\db\Expression('CAST(del.response_datetime AS DATE)'), !empty($this->response_datetime) ? date('Y-m-d', strtotime($this->response_datetime)) : NULL]);


        $query->andFilterWhere(['like', 'del.update_key', $this->update_key])
                ->andFilterWhere(['like', 'del.data_post_status', $this->data_post_status])
                ->andFilterWhere(['like', 'del.originating_type', $this->originating_type])
                ->andFilterWhere(['like', 'del.process_name', $this->process_name])
                ->andFilterWhere(['like', 'del.originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'del.originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'del.resp_status', $this->resp_status])
                ->andFilterWhere(['like', 'del.resp_desc', $this->resp_desc])
                ->andFilterWhere(['like', 'del.resp_msg', $this->resp_msg])
                ->andFilterWhere(['like', 'del.resp_param_1', $this->resp_param_1])
                ->andFilterWhere(['like', 'del.resp_param_2', $this->resp_param_2])
                ->andFilterWhere(['like', 'del.resp_param_3', $this->resp_param_3])
                ->andFilterWhere(['like', 'del.resp_param_4', $this->resp_param_4])
                ->andFilterWhere(['like', 'del.resp_param_5', $this->resp_param_5])
                ->andFilterWhere(['like', 'del.resp_param_6', $this->resp_param_6]);

        return $dataProvider;
    }

    public function searchLogData($params) {
        $query = TblMemberProvisional::find()->alias('mp');

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'mp', 'mp', 'mp', 'mp');

        if ($this->process_name == 'Member Provisional Family Detail') {
            $query->innerJoin(['f' => 'tbl_member_provisional_family_details'], 'f.provisional_member_code = mp.provisional_member_code');

            $query->leftJoin(['del' => 'tbl_data_exchange_log'], "
         (del.process_name = 'Member Provisional Family Detail' AND del.process_code = f.member_provisional_family_detail_code)");

            $query->select([
                'mp.provisional_member_code',
                'mp.application_no AS application_no',
                'mp.dcs_code AS dcs_code',
                'f.family_member_name AS name',
                'f.member_provisional_family_detail_code AS code',
                'del.*',
            ]);
        } else {
            $query->leftJoin(['del' => 'tbl_data_exchange_log'], "(del.process_name = 'Member Provisional' AND del.process_code = mp.provisional_member_code)
        ");

            $query->select([
                'mp.provisional_member_code AS code',
                'mp.provisional_member_code AS provisional_member_code',
                'mp.member_name as name',
                'mp.application_no',
                'mp.application_no AS mp_application_no',
                'mp.dcs_code AS dcs_code',
                'del.*',
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->asArray(),
            'pagination' => false,
        ]);
        $query->andWhere(['is not ', 'mp.application_no', null]);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        if ($this->data_post_status != null && $this->data_post_status != '' && $this->data_post_status != 0) {
            $query->andWhere(['del.data_post_status' => $this->data_post_status]);
            $query->andWhere(['del.process_name' => $this->process_name]);
        } else if ((int) $this->data_post_status === 0) {
            $query->andWhere(['del.process_code' => null]);
        }

        if (!empty($this->from_date) && !empty($this->to_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andWhere(['between', 'CAST(mp.created_at AS DATE)', $from_date, $to_date]);
        } else {
            $query->andWhere(['CAST(mp.created_at AS DATE)' => date('Y-m-d')]);
        }

        return $dataProvider;
    }

    public function searchHistory($params) {
        $query = TblDataExchangeLogHistory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);
        $query->andWhere(['process_code' => $this->process_code]);


        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function validateToDate($attribute, $params) {
        if (!empty($this->from_date) && !empty($this->to_date)) {
            $fDate = date('Y-m-d', strtotime($this->from_date));
            $tDate = date('Y-m-d', strtotime($this->to_date));
            if ($tDate < $fDate) {
                $this->addError($attribute, Yii::t('app/validation', 'To Date must be greater than From Date'));
                return false;
            } else {
                $fDate = date_create($fDate);
                $tDate = date_create($tDate);
                $diff = date_diff($fDate, $tDate);
                $DayCount = $diff->format("%a");
                $DayCount = $DayCount + 1;
                if ($DayCount > 15) {
                    $this->addError('to_date', Yii::t('app/validation', 'Day Difference can not be greater than 15.'));
                    return false;
                }
            }
        }
    }

}
