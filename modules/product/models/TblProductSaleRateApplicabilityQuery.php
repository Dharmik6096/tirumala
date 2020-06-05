<?php

namespace app\modules\product\models;

/**
 * This is the ActiveQuery class for [[TblProductSaleRateApplicability]].
 *
 * @see TblProductSaleRateApplicability
 */
class TblProductSaleRateApplicabilityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblProductSaleRateApplicability[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblProductSaleRateApplicability|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
