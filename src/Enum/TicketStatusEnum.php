<?php

namespace ModernGame\Enum;

use SplEnum;

class TicketStatusEnum extends SplEnum
{
    const NOT_READ = 1;
    const ASSIGN_AS_READ = 2;
}
