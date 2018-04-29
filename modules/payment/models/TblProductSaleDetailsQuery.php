<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblProductSaleDetails]].
 *
 * @see TblProductSaleDetails
 */
class TblProductSaleDetailsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblProductSaleDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblProductSaleDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
