<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblDcsConfig]].
 *
 * @see TblDcsConfig
 */
class TblDcsConfigQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsConfig[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsConfig|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
