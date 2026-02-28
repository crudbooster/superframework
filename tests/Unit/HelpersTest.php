<?php

namespace Tests\Unit;

use Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_minify_asset_css()
    {
        $css = "body { color: red; } /* comment */";
        $result = minify_asset('css', $css);
        $this->assertEquals("body{color:red;}", $result);
    }

    public function test_minify_asset_js()
    {
        $js = "function test() { console.log('test'); }";
        $result = minify_asset('js', $js);
        $this->assertEquals("function test() { console.log('test'); }", $result);
    }

    public function test_asset_url()
    {
        $url = asset_url('/css/style.css');
        $this->assertEquals("/assets/css/style.css", $url);
    }
}
