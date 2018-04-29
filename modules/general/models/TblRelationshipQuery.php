<?php

namespace app\modules\general\models;

/**
 * This is the ActiveQuery class for [[TblRelationship]].
 *
 * @see TblRelationship
 */
class TblRelationshipQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblRelationship[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblRelationship|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
