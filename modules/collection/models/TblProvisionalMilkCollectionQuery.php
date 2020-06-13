<?php

namespace app\modules\collection\models;

/**
 * This is the ActiveQuery class for [[TblProvisionalMilkCollection]].
 *
 * @see TblProvisionalMilkCollection
 */
class TblProvisionalMilkCollectionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblProvisionalMilkCollection[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblProvisionalMilkCollection|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
