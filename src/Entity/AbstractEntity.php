<?php

namespace Yakuzan\Boiler\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class AbstractEntity extends Model
{
    protected $access_rules = [];

    protected $modify_rules = [];

    public function access_rules(Request $request): array
    {
        return $this->access_rules;
    }

    public function modify_rules(Request $request): array
    {
        return $this->modify_rules;
    }

    public function access_rules_attributes(): array
    {
        return $this->fillable;
    }

    public function modify_rules_attributes(): array
    {
        return $this->fillable;
    }
}
