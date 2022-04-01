<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use QCod\Gamify\Reputation;

class Quest extends Model
{
    use HasFactory;

    public function isRepeatable() {
        return $this->repeatable;
    }

    public function isMultipliable() {
        return $this->multipliable;
    }

    public function isRequirementFulfill(User $user, $subject = null): bool {
        $related = $this->requirement_user_relation;
        $criteria1 = $user->$related()->count();
        if($this->hasRequirementField()) {
            $field = $this->requirement_field;
            if($subject !== null) {
                $criteria1 = $user->$field;
            }
            else {
                if(($user->$related instanceof Collection) && $subject == null) {
                    throw new \Exception('Can not parse field from collection, use object as second argument instead');
                }
                $criteria1 = $subject->$related?->$field;
            }
        }
        return $this->criteriaMet($criteria1, $this->requirement_value, $this->requirement_operator);
    }

    public function hasRequirementField(): bool {
        return $this->requirement_field !== null;
    }

    protected function criteriaMet($value1, $value2, $operator = '=')
    {
        switch ($operator) {
            case '<':
                return $value1 < $value2;
            case '<=':
                return $value1 <= $value2;
            case '>':
                return $value1 > $value2;
            case '>=':
                return $value1 >= $value2;
            case '==':
            case '=':
                return $value1 == $value2;
            case '!=':
            case '<>':
                return $value1 != $value2;
            case '%':
                return $value1 % $value2 == 0;
            default:
                return false;
        }
    }

    protected function isLimited() {
        return $this->quota != null || $this->limit_interval != null;
    }

    public function isQuotaReached() {
        return $this->isLimited() && $this->reputations()->count() >= $this->quota;
    }

    public function isTakenBy(User $user) {
        return $this->getUserReputation($user)->count() > 0;
    }

    public function getUserReputations($user) {
        return $this->reputations()->where('payee_id', $user->id);
    }

    public function canTakeBy(User $user): bool
    {
        return $this->limit_per_user != null && $this->limit_per_user <= $this->getUserReputations($user)->count();
    }

    public function getPoint(mixed $subject) {
        if($this->isMultipliable() && $this->hasRequirementField()) {
            $field = $this->requirement_field;
            $multiplier = floor($subject->$field / $this->requirement_value);
            return $this->point * $multiplier;
        }
        return $this->point;
    }

    public function reputations() {
        return $this->hasMany(Reputation::class, 'quest_id');
    }
}
