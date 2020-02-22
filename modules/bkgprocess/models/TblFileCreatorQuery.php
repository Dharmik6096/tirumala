<?php

namespace app\modules\bkgprocess\models;

/**
 * This is the ActiveQuery class for [[TblFileCreator]].
 *
 * @see TblFileCreator
 */
class TblFileCreatorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblFileCreator[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblFileCreator|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
