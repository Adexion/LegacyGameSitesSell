<?php

namespace ModernGame\Service\Content\Regulation;

use ModernGame\Dto\RuleDto;

class RegulationMapper
{
    private $ruleList = [];
    private $category = '';

    /** @var RuleDto */
    private $ruleDto;

    public function mapRules(array $regulation): array
    {
        /** @var array $rule */
        foreach ($regulation as $rule) {
            $this->setRuleDto($rule);

            $this->ruleDto->addRules($rule['description']);
        }

        if (isset($this->ruleDto)) {
            $this->ruleList[] = $this->ruleDto;
        }

        return $this->ruleList;
    }

    private function setRuleDto(array $rule)
    {
        if ($this->category !== $rule['category']) {
            if (isset($this->ruleDto)) {
                $this->ruleList[] = $this->ruleDto;
            }

            $this->ruleDto = new RuleDto($this->category = $rule['category']);
        }
    }
}
