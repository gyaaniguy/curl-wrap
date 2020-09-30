<?php

namespace gyaani\guy\Classes;


class WorkTest extends \PHPUnit\Framework\TestCase
{

    public function testWorkfunc()
    {
        $w = new Work();
        self::assertTrue($w->workfunc() ===1);
    }
}
