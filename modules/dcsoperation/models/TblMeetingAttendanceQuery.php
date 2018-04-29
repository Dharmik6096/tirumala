<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblMeetingAttendance]].
 *
 * @see TblMeetingAttendance
 */
class TblMeetingAttendanceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMeetingAttendance[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMeetingAttendance|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
