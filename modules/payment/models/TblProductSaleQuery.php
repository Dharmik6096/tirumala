<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblProductSale]].
 *
 * @see TblProductSale
 */
class TblProductSaleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblProductSale[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblProductSale|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
