<?php

return [
  // Driver available : mysql, sqlsrv, pgsql, sqlite
  "driver"      => $_ENV['DB_CONNECTION'],
  "host"        => $_ENV['DB_HOST'],
  "port"        => $_ENV['DB_PORT'],
  "database"    => $_ENV['DB_DATABASE'],
  "username"    => $_ENV['DB_USERNAME'],
  "password"    => $_ENV['DB_PASSWORD']
];