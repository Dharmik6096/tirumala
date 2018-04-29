<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblLocalMilkSale]].
 *
 * @see TblLocalMilkSale
 */
class TblLocalMilkSaleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblLocalMilkSale[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblLocalMilkSale|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
