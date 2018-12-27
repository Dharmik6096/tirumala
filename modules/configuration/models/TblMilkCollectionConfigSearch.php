<?php

namespace app\modules\configuration\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblMilkCollectionConfig;

/**
 * TblMilkCollectionConfigSearch represents the model behind the search form about `app\modules\configuration\models\TblMilkCollectionConfig`.
 */
class TblMilkCollectionConfigSearch extends TblMilkCollectionConfig
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'accept_milk', 'can_per_ltr', 'collection_quantity_mode', 'bmc_collection_quantity_mode', 'local_milk_sale_quantity_mode', 'sample_milk_quantity_mode', 'default_snf', 'from_machine_clr', 'no_disp_local_sale', 'per_local_sale', 'input_clr', 'multi_entry_diff_milk_type', 'multi_entry_same_milk_type', 'no', 'no_disp', 'seperate_can', 'shift_code', 'shift_code_disp', 'variation_in_fat_block', 'variation_in_qty_block', 'variation_in_snf_block', 'weight_setting'], 'integer'],
            [['based_on', 'based_on_disp', 'collection_mode', 'created_at', 'created_by', 'based_on_local_sale', 'updated_at', 'updated_by', 'union_code'], 'safe'],
            [['can_warning_per', 'default_snf_value', 'lr1_for_clr', 'lr2_for_clr', 'ltr_to_kg', 'sample_milk_size', 'variation_in_fat', 'variation_in_qty', 'variation_in_snf'], 'number'],
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
        $query = TblMilkCollectionConfig::find();

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
            'code' => $this->code,
            'accept_milk' => $this->accept_milk,
            'can_per_ltr' => $this->can_per_ltr,
            'can_warning_per' => $this->can_warning_per,
            'collection_quantity_mode' => $this->collection_quantity_mode,
            'bmc_collection_quantity_mode' => $this->bmc_collection_quantity_mode,
            'local_milk_sale_quantity_mode' => $this->local_milk_sale_quantity_mode,
            'sample_milk_quantity_mode' => $this->sample_milk_quantity_mode,
            'created_at' => $this->created_at,
            'default_snf' => $this->default_snf,
            'default_snf_value' => $this->default_snf_value,
            'from_machine_clr' => $this->from_machine_clr,
            'no_disp_local_sale' => $this->no_disp_local_sale,
            'per_local_sale' => $this->per_local_sale,
            'input_clr' => $this->input_clr,
            'lr1_for_clr' => $this->lr1_for_clr,
            'lr2_for_clr' => $this->lr2_for_clr,
            'ltr_to_kg' => $this->ltr_to_kg,
            'multi_entry_diff_milk_type' => $this->multi_entry_diff_milk_type,
            'multi_entry_same_milk_type' => $this->multi_entry_same_milk_type,
            'no' => $this->no,
            'no_disp' => $this->no_disp,
            'sample_milk_size' => $this->sample_milk_size,
            'seperate_can' => $this->seperate_can,
            'shift_code' => $this->shift_code,
            'shift_code_disp' => $this->shift_code_disp,
            'updated_at' => $this->updated_at,
            'variation_in_fat' => $this->variation_in_fat,
            'variation_in_fat_block' => $this->variation_in_fat_block,
            'variation_in_qty' => $this->variation_in_qty,
            'variation_in_qty_block' => $this->variation_in_qty_block,
            'variation_in_snf' => $this->variation_in_snf,
            'variation_in_snf_block' => $this->variation_in_snf_block,
            'weight_setting' => $this->weight_setting,
        ]);

        $query->andFilterWhere(['like', 'based_on', $this->based_on])
            ->andFilterWhere(['like', 'based_on_disp', $this->based_on_disp])
            ->andFilterWhere(['like', 'collection_mode', $this->collection_mode])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'based_on_local_sale', $this->based_on_local_sale])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }
}
