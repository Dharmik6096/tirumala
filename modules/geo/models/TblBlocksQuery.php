<?php

namespace app\modules\geo\models;

/**
 * This is the ActiveQuery class for [[TblBlocks]].
 *
 * @see TblBlocks
 */
class TblBlocksQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblBlocks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblBlocks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
