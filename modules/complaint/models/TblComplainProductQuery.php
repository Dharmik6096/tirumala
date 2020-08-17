<?php

namespace app\modules\complaint\models;

/**
 * This is the ActiveQuery class for [[TblComplainProduct]].
 *
 * @see TblComplainProduct
 */
class TblComplainProductQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblComplainProduct[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblComplainProduct|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
