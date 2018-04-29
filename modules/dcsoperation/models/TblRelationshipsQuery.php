<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblRelationships]].
 *
 * @see TblRelationships
 */
class TblRelationshipsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblRelationships[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblRelationships|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
