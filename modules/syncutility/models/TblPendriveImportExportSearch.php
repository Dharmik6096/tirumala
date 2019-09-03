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
            [['id', 'no_of_records', 'no_of_records_ignore', 'download_counter'], 'safe'],
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
        $query = TblPendriveImportExport::find()->where(['tbl_pendrive_import_export.mode' => 1]);
        //$query->joinWith([ 'unionCode.federationCode']);
        $query->andWhere(['tbl_pendrive_import_export.x_col1' => 'PEN_DRIVE']);
        $query->orderBy('tbl_pendrive_import_export.created_at desc');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'no_of_records' => $this->no_of_records,
        ]);

        $query->andFilterWhere(['like', 'tbl_pendrive_import_export.union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_pendrive_import_export.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'file_name', $this->file_name]);

        return $dataProvider;
    }

}
