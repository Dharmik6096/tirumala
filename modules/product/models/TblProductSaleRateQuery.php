<?php

namespace app\modules\product\models;

/**
 * This is the ActiveQuery class for [[TblProductSaleRate]].
 *
 * @see TblProductSaleRate
 */
class TblProductSaleRateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblProductSaleRate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblProductSaleRate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
