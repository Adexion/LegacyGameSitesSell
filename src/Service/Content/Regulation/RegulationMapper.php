<?php

namespace MNGame\Service\Content\Regulation;

use MNGame\Dto\RuleDto;
use MNGame\Dto\RuleListDto;

class RegulationMapper
{
    private RuleListDto $ruleListDto;

    public function __construct()
    {
        $this->ruleListDto = new RuleListDto();
    }

    public function mapRules(array $regulation): array
    {
        /** @var array $rule */
        foreach ($regulation as $rule) {
            $this->getRuleDto($rule)->addRule($rule['description']);
        }

        return $this->ruleListDto->toArray();
    }

    private function getRuleDto(array $rule): RuleDto
    {
        if ($this->ruleListDto->isRuleWithCategoryExist($rule['categoryId'])) {
            return $this->ruleListDto->getRule($rule['categoryId']);
        }

        $ruleDto = new RuleDto($rule['categoryName']);
        $this->ruleListDto->setRule($rule['categoryId'], $ruleDto);

        return $ruleDto;
    }
}
