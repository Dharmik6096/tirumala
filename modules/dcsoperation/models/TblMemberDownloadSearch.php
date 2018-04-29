<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMemberDownload;

/**
 * TblMemberDownloadSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMemberDownload`.
 */
class TblMemberDownloadSearch extends TblMemberDownload {

    public $union_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['download_id', 'is_download'], 'integer'],
            [['dcs_code', 'download_datetime', 'upload_datetime', 'created_at', 'created_by', 'updated_at', 'updated_by','union_code'], 'safe'],
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
        $query = TblMemberDownload::find();
        $query->joinWith(['dcsCode','dcsCode.unionCode']);
        $query->where(['is_download'=>1]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (Yii::$app->session->get('Unions') !== '' && empty($this->union_code))
            $query->andFilterWhere([ 'tbl_unions' . '.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        else
            $query->andwhere(['tbl_unions' . '.union_code' => $this->union_code]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->upload_datetime))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), upload_datetime, 126)', date('Y-m-d', strtotime($this->upload_datetime))]);

        // grid filtering conditions
//        $query->andFilterWhere([
//          //  'is_download' => $this->is_download,
//          //  'download_datetime' => $this->download_datetime,
//          //  'upload_datetime' => $this->upload_datetime,
//        ]);

        $query->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code]);

        return $dataProvider;
    }

}
