<?php

namespace app\modules\globalmaster\models;
/**
 * This is the ActiveQuery class for [[TblDcsTypes]].
 *
 * @see TblDcsTypes
 */
class TblDcsTypesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsTypes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsTypes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
