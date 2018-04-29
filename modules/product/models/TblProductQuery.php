<?php

namespace app\modules\product\models;

/**
 * This is the ActiveQuery class for [[TblProduct]].
 *
 * @see TblProduct
 */
class TblProductQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblProduct[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblProduct|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
