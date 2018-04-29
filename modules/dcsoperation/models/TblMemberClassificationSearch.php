<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMemberClassification;

/**
 * TblMemberClassificationSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMemberClassification`.
 */
class TblMemberClassificationSearch extends TblMemberClassification
{
    public $federation_code;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_classification_code','federation_code','local_name', 'range_from', 'member_classification_type', 'range_to', 'created_at', 'created_by', 'member_classification_name', 'sync_status', 'updated_at', 'updated_by', 'union_code'], 'safe'],
            [['is_active',], 'boolean'],
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
        $query = TblMemberClassification::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        
        if (Yii::$app->general->organizationSessionCheck()){
            
            $query->leftJoin('tbl_member_classification_local', '`tbl_member_classification`.`member_classification_code` = `tbl_member_classification_local`.`member_classification_code` '
                    . ' AND tbl_member_classification_local.language_code="'.Yii::$app->session->get('LanguageId').'"');
            /*$query->joinWith(['tblHamletsLocals',]);
            $query->andFilterWhere(['tbl_hamlets_local.language_code'=>Yii::$app->session->get('LanguageId')]);*/
        }
        
        $query->joinWith(['unionCode.federationCode']);        
        
        if(Yii::$app->session->get('Unions')!==''){
            $query->andFilterWhere([ 'tbl_member_classification.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }else
            $query->andFilterWhere([ 'tbl_member_classification.union_code'=>$this->union_code]);
        

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_member_classification.is_active' => $this->is_active,
            'range_from' => $this->range_from,
            'range_to' => $this->range_to,
        ]);

        $query->andFilterWhere(['like', 'member_classification_type', $this->member_classification_type])
            ->andFilterWhere(['like', 'tbl_member_classification.member_classification_code', $this->member_classification_code])
            ->andFilterWhere(['like', 'member_classification_name', $this->member_classification_name])
           ->andFilterWhere(['like', 'tbl_member_classification.union_code', $this->union_code]);

        return $dataProvider;
    }
}
