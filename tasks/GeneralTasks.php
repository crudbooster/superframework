<?php

use Crunz\Schedule;

# Run Task CLI
# * * * * * * cd /path/html/project/ && /var/bin/php super schedule:run

$schedule = new Schedule();

# Example
//$task = $schedule->run(PHP_BINARY. ' super feed:run');
//$task->daily()->description("Run feed content");

return $schedule;