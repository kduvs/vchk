<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "log_issuing".
 *
 * @property int $id
 * @property int $book_id
 * @property int $owner_id
 * @property int $issuing_id
 * @property int $recipient_id
 * @property int $taker_id
 * @property string $issue_time Дата выдачи
 * @property string $return_time Дата возврата
 *
 * @property Books $book
 * @property User $issuing
 * @property Owners $owner
 * @property User $recipient
 * @property User $taker
 */
class LogIssuing extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_issuing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id', 'owner_id', 'taker_id', 'issue_time', 'deadline'], 'required'],
            [['book_id', 'owner_id', 'issuing_id', 'recipient_id', 'taker_id'], 'integer'],
            [['issue_time', 'return_time', 'deadline'], 'safe'],
            [['message'], 'string'],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::className(), 'targetAttribute' => ['book_id' => 'id']],
            [['issuing_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['issuing_id' => 'id']],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Owners::className(), 'targetAttribute' => ['owner_id' => 'id']],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['recipient_id' => 'id']],
            [['taker_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['taker_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book ID',
            'owner_id' => 'Owner ID',
            'issuing_id' => 'Issuing ID',
            'recipient_id' => 'Recipient ID',
            'taker_id' => 'Taker ID',
            'issue_time' => 'Дата выдачи',
            'return_time' => 'Дата возврата',
            'deadline' => 'Дедлайн',
            'message' => 'Сообщение',
        ];
    }

    public function getBook()
    {
        return $this->hasOne(Books::className(), ['id' => 'book_id']);
    }

    public function getIssuing()
    {
        return $this->hasOne(User::className(), ['id' => 'issuing_id']);
    }

    public function getOwner()
    {
        return $this->hasOne(Owners::className(), ['id' => 'owner_id']);
    }

    public function getRecipient()
    {
        return $this->hasOne(User::className(), ['id' => 'recipient_id']);
    }

    public function getTaker()
    {
        return $this->hasOne(User::className(), ['id' => 'taker_id']);
    }
}
