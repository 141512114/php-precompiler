<?php

namespace General\File\Include;

enum IncludeType: string
{
    case REQUIRE = 'require';
    case INCLUDE = 'include';
    case REQUIRE_ONCE = 'require_once';
    case INCLUDE_ONCE = 'include_once';
}
