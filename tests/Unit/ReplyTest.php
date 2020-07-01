<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ReplyTest extends TestCase
{
    public function it_has_an_owner()
    {
        $reply = factory('App\Reply')->create();

        $this->assertInstanceOf('App\User', $reply->owner);
    }
}
