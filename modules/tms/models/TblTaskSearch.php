<?php

namespace app\modules\tms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tms\models\TblTask;
use app\modules\usermanagement\models\User;

/**
 * TblTaskSearch represents the model behind the search form about `app\modules\tms\models\TblTask`.
 */
class TblTaskSearch extends TblTask {

    public $from_date, $to_date, $bmc_name, $user_name, $department_wise = 1;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['task_code', 'task_type_code', 'form_type_code', 'is_cancel', 'is_notified', 'originating_type'], 'integer'],
                [['task_performed_for', 'title', 'description', 'status', 'user_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'notified_datetime', 'pick_datetime', 'response_datetime', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'department_wise'], 'safe'],
                [['from_date', 'to_date', 'bmc_name', 'task_datetime', 'user_name'], 'safe']
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
        $query = TblTask::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['userCode', 'taskTypeCode', 'formTypeCode', 'bmcCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_task', 'tbl_task', 'tbl_task');

        $from_date = (!empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d')) . ' 00:00:00';
        $to_date = (!empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d')) . ' 23:59:00';

        $query->andFilterWhere(['>=', 'tbl_task.task_datetime', $from_date]);
        $query->andFilterWhere(['<=', 'tbl_task.task_datetime', $to_date]);
        
        if (!empty($this->task_datetime)) {
            $query->andFilterWhere(['like', 'CAST(tbl_task.task_datetime AS DATE)', date('Y-m-d', strtotime($this->task_datetime))]);
        }
        if($this->department_wise != 0) {
            $department = User::find()->select('department')->where(['id' => Yii::$app->session->get('UserCode')])->scalar();
            !empty($department) && $query->andWhere(['user.department' => $department]);
        }
        $query->andFilterWhere([
            'is_cancel' => $this->is_cancel,
            'is_notified' => $this->is_notified,
        ]);
        
        $query->andFilterWhere(['like', 'tbl_task.task_performed_for', $this->task_performed_for])
                ->andFilterWhere(['like', 'tbl_task.title', $this->title])
                ->andFilterWhere(['like', 'tbl_task.description', $this->description])
                ->andFilterWhere(['like', 'tbl_task.status', $this->status])
                ->andFilterWhere(['like', 'tbl_task_type.task_type_code', $this->task_type_code])
                ->andFilterWhere(['like', 'tbl_form_type.form_type_code', $this->form_type_code])
                ->andFilterWhere(['like', '[user].name', $this->user_name])
                ->andFilterWhere(['like', 'tbl_task.task_code', $this->task_code])
                ->andFilterWhere(['like', 'tbl_task.bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code]);

        $query->orderBy(['tbl_task.task_datetime' => SORT_DESC, '[user].name' => SORT_ASC]);
        return $dataProvider;
    }

}
