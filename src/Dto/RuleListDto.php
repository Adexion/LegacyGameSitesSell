<?php

namespace MNGame\Dto;

class RuleListDto
{
    private array $ruleList = [];

    public function getRule(string $category): RuleDto
    {
        return $this->ruleList[$category];
    }

    public function setRule(string $category, RuleDto $ruleDto)
    {
        $this->ruleList[$category] = $ruleDto;
    }

    public function isRuleWithCategoryExist(string $category): bool
    {
        return array_key_exists($category, $this->ruleList);
    }

    public function toArray()
    {
        return array_values($this->ruleList);
    }
}
