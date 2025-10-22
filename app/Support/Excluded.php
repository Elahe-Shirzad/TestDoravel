<?php

namespace App\Support;

class Excluded
{
    public const CONSOLE_COMMANDS = [
        "vendor:publish",
        "package:discover",
        "migrate",
        "optimize:clear",
        "storage:link",
    ];
}
