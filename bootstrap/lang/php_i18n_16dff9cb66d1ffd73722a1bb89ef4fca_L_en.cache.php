<?php class L {
const default_documentation = 'Documentation';
const AdminAuth_login = 'Login';
const AdminAuth_auth = 'Auth';
public static function __callStatic($string, $args) {
    return vsprintf(constant("self::" . $string), $args);
}
}
function L($string, $args=NULL) {
    $return = constant("L::".$string);
    return $args ? vsprintf($return,$args) : $return;
}