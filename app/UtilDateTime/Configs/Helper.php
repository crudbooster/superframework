<?php

if(!function_exists('carbon')) {
    /**
     * @return \Carbon\Carbon
     */
    function carbon() {
        return (new Carbon\Carbon());
    }
}