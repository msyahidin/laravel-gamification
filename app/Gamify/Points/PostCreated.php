<?php

namespace App\Gamify\Points;

use App\Models\Quest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use QCod\Gamify\PointType;

class PostCreated extends PointType
{
    /**
     * Number of points
     *
     * @var int
     */
    public $points = 20;

    public static string $eventName;
    private static string $questClass = Quest::class;
    private Quest $quest;

    /**
     * Point constructor
     *
     * @param $subject
     */
    public function __construct($subject)
    {
        $this->subject = $subject;
        $this->quest = self::getQuest();
    }

    /**
     * User who will be receive points
     *
     * @return mixed
     * @throws \QCod\Gamify\Exceptions\PointSubjectNotSet
     */
    public function payee()
    {
        return $this->getSubject()->user;
    }

    public static function getQuest() {
        $query = self::$questClass::where('event_name', self::getEventName());
        if(!$query->exists()) {
            throw (new ModelNotFoundException('Event not found'));
        }
        return $query->first();
    }

    public static function getEventName() {
        return self::$eventName ?? class_basename(__CLASS__);
    }

    public function getPoints()
    {
        return $this->quest->getPoint($this->subject);
    }

    public function qualifier()
    {
        return $this->reputationExists() && $this->quest->isRepeatable();
    }

    public function getMeta() {
        return [
            'event' => $this->quest->toArray()
        ];
    }

    public function storeReputation($meta = [])
    {
        return parent::storeReputation($this->getMeta());
    }

    public function isEventRepeateable() {
        return $this->reputationExists() && !$this->quest->isRepeatable();
    }

    public function isMultipliable() {
        return $this->quest->multipliable;
    }
}
