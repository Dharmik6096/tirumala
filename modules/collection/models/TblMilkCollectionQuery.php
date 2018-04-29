<?php

namespace app\modules\collection\models;

/**
 * This is the ActiveQuery class for [[TblMilkCollection]].
 *
 * @see TblMilkCollection
 */
class TblMilkCollectionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMilkCollection[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMilkCollection|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
