<?php

namespace app\modules\product\models;

/**
 * This is the ActiveQuery class for [[TblProductRateApplicability]].
 *
 * @see TblProductRateApplicability
 */
class TblProductRateApplicabilityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblProductRateApplicability[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblProductRateApplicability|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
