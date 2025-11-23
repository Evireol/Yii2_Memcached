<?php
namespace app\models;

use Yii;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $text
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class Todo extends ActiveRecord
{
    const STATUSES = ['pending', 'in_progress', 'completed'];

    public static function tableName()
    {
        return '{{%todo}}';
    }

    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => self::STATUSES],
        ];
    }
}
