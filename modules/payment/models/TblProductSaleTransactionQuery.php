<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblProductSaleTransaction]].
 *
 * @see TblProductSaleTransaction
 */
class TblProductSaleTransactionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblProductSaleTransaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblProductSaleTransaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
