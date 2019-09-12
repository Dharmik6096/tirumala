<?php

namespace app\modules\syncutility\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\syncutility\models\TblPendriveImportExport;

/**
 * TblPendriveImportExportSearch represents the model behind the search form about `app\modules\syncutility\models\TblPendriveImportExport`.
 */
class TblPendriveImportExportSearch extends TblPendriveImportExport {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'no_of_records', 'no_of_records_ignore', 'download_counter', 'device_id'], 'safe'],
            [['union_code', 'dcs_code', 'file_name', 'created_at', 'created_by', 'deleted_by', 'deleted_at', 'updated_at', 'updated_by'], 'safe'],
            [['mode'], 'safe'],
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
        $query = TblPendriveImportExport::find();
        $query->orderBy('tbl_pendrive_import_export.created_at desc');
        // add conditions that should always apply here
        $query->joinWith(['dcsCode']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_pendrive_import_export.no_of_records' => $this->no_of_records,
            'tbl_pendrive_import_export.mode' => $this->mode,
            'tbl_pendrive_import_export.download_counter' => $this->download_counter
        ]);

        $query->andFilterWhere(['like', 'tbl_pendrive_import_export.device_id', $this->device_id])
                ->andFilterWhere(['like', 'tbl_pendrive_import_export.file_name', $this->file_name]);

        return $dataProvider;
    }

}
