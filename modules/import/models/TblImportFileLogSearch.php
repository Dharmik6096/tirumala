<?php

namespace app\modules\import\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\import\models\TblImportFileLog;

/**
 * TblImportFileLogSearch represents the model behind the search form about `app\modules\import\models\TblImportFileLog`.
 */
class TblImportFileLogSearch extends TblImportFileLog {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['log_id', 'total_count', 'success_count', 'error_count', 'status'], 'safe'],
            [['file_type', 'file_name', 'file_path', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'pick_datetime', 'response_datetime', 'response_msg', 'error_file_path'], 'safe'],
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
        $query = TblImportFileLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['userCode']);
        $this->load($params);
        $query->where(['union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        if (Yii::$app->session->get('organizations_type') != 'UNION') {
            $query->andWhere(['created_by' => Yii::$app->session->get('UserCode')]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'log_id' => $this->log_id,
            'total_count' => $this->total_count,
            'success_count' => $this->success_count,
            'error_count' => $this->error_count,
//            'tbl_import_file_log.created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tbl_import_file_log.status' => $this->status,
//            'pick_datetime' => $this->pick_datetime,
        ]);
        if (!empty($this->created_at)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_import_file_log.created_at, 126)', date('Y-m-d', strtotime($this->created_at))]);
        }
        if (!empty($this->pick_datetime)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_import_file_log.pick_datetime, 126)', date('Y-m-d', strtotime($this->pick_datetime))]);
        }
        if (!empty($this->response_datetime)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_import_file_log.response_datetime, 126)', date('Y-m-d', strtotime($this->response_datetime))]);
        }
        $query->andFilterWhere(['like', 'file_type', $this->file_type])
                ->andFilterWhere(['like', 'file_name', $this->file_name])
                ->andFilterWhere(['like', 'file_path', $this->file_path])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'user.name', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'response_msg', $this->response_msg])
                ->andFilterWhere(['like', 'error_file_path', $this->error_file_path]);
        return $dataProvider;
    }

}
