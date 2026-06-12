<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblNarration;

/**
 * TblNarrationSearch
 */
class TblNarrationSearch extends TblNarration
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['narration_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'narration', 'narration_local', 'dcs_code', 'narration_type_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_active', 'x_col1', 'x_col2', 'x_col3'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
        $query = TblNarration::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs', 'tbl_dcs', 'tbl_dcs', 'tbl_narration');

        $query->andFilterWhere([
            'tbl_narration.is_active' => $this->is_active,
            'tbl_narration.narration_type_code' => $this->narration_type_code,
        ]);

        $query->andFilterWhere(['like', 'tbl_narration.narration_code', $this->narration_code])
            ->andFilterWhere(['like', 'tbl_narration.narration', $this->narration])
            ->andFilterWhere(['like', 'tbl_narration.narration_local', $this->narration_local]);

        return $dataProvider;
    }
}
