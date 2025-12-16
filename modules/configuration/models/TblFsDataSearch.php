<?php

namespace app\modules\configuration\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblFsData;
use Yii;

/**
 * TblFsDataSearch represents the model behind the search form about `app\modules\configuration\models\TblFsData`.
 */
class TblFsDataSearch extends TblFsData {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['fs_data_code', 'originating_type'], 'integer'],
            [['union_code', 'dcs_code', 'dsrn', 'dtyp', 'dlock', 'dscch', 'dsbch', 'dsmch', 'dsfd', 'dssd', 'dssdm', 'dscc', 'dsai', 'dsas', 'dsmf', 'dsms', 'dsht', 'dsct', 'docfo', 'docso', 'docwo', 'docdo', 'docpo', 'doclo', 'dobfo', 'dobso', 'dobwo', 'dobdo', 'dobpo', 'doblo', 'domfo', 'domso', 'domwo', 'domdo', 'dompo', 'domlo', 'dpp1', 'dpp2', 'dpp3', 'download_datetime', 'processed_datetime', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'dcs_name'], 'safe'],
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
        $query = TblFsData::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->download_datetime)) {
            $query->andFilterWhere(['CAST(tbl_fs_data.download_datetime as date)' => date('Y-m-d', strtotime($this->download_datetime))]);
        }

        if (!empty($this->processed_datetime)) {
            $query->andFilterWhere(['CAST(tbl_fs_data.processed_datetime as date)' => date('Y-m-d', strtotime($this->processed_datetime))]);
        }

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_name])
                ->andFilterWhere(['like', 'dsrn', $this->dsrn])
                ->andFilterWhere(['like', 'dtyp', $this->dtyp])
                ->andFilterWhere(['like', 'dlock', $this->dlock])
                ->andFilterWhere(['like', 'dscch', $this->dscch])
                ->andFilterWhere(['like', 'dsbch', $this->dsbch])
                ->andFilterWhere(['like', 'dsmch', $this->dsmch])
                ->andFilterWhere(['like', 'dsfd', $this->dsfd])
                ->andFilterWhere(['like', 'dssd', $this->dssd])
                ->andFilterWhere(['like', 'dssdm', $this->dssdm])
                ->andFilterWhere(['like', 'dscc', $this->dscc])
                ->andFilterWhere(['like', 'dsai', $this->dsai])
                ->andFilterWhere(['like', 'dsas', $this->dsas])
                ->andFilterWhere(['like', 'dsmf', $this->dsmf])
                ->andFilterWhere(['like', 'dsms', $this->dsms])
                ->andFilterWhere(['like', 'dsht', $this->dsht])
                ->andFilterWhere(['like', 'dsct', $this->dsct])
                ->andFilterWhere(['like', 'docfo', $this->docfo])
                ->andFilterWhere(['like', 'docso', $this->docso])
                ->andFilterWhere(['like', 'docwo', $this->docwo])
                ->andFilterWhere(['like', 'docdo', $this->docdo])
                ->andFilterWhere(['like', 'docpo', $this->docpo])
                ->andFilterWhere(['like', 'doclo', $this->doclo])
                ->andFilterWhere(['like', 'dobfo', $this->dobfo])
                ->andFilterWhere(['like', 'dobso', $this->dobso])
                ->andFilterWhere(['like', 'dobwo', $this->dobwo])
                ->andFilterWhere(['like', 'dobdo', $this->dobdo])
                ->andFilterWhere(['like', 'dobpo', $this->dobpo])
                ->andFilterWhere(['like', 'doblo', $this->doblo])
                ->andFilterWhere(['like', 'domfo', $this->domfo])
                ->andFilterWhere(['like', 'domso', $this->domso])
                ->andFilterWhere(['like', 'domwo', $this->domwo])
                ->andFilterWhere(['like', 'domdo', $this->domdo])
                ->andFilterWhere(['like', 'dompo', $this->dompo])
                ->andFilterWhere(['like', 'domlo', $this->domlo])
                ->andFilterWhere(['like', 'dpp1', $this->dpp1])
                ->andFilterWhere(['like', 'dpp2', $this->dpp2])
                ->andFilterWhere(['like', 'dpp3', $this->dpp3]);

        return $dataProvider;
    }

}
