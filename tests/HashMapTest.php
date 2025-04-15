<?php

declare(strict_types=1);

namespace DNW\Skills\Tests;

use DNW\Skills\HashMap;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use stdClass;

#[CoversClass(HashMap::class)]
final class HashMapTest extends TestCase
{
    public function testHashmap(): void
    {
        $h = new HashMap();

        $this->assertEquals([], $h->getAllKeys());
        $this->assertEquals([], $h->getAllValues());


        $o1 = new stdClass();
        $o2 = new stdClass();

        $h->setValue($o1, 1);
        $h->setvalue($o2, 2);

        $this->assertEquals([1, 2], $h->getAllValues());
        $this->assertEquals([$o1, $o2], $h->getAllKeys());

        $this->assertEquals(1, $h->getvalue($o1));
        $this->assertEquals(2, $h->getvalue($o2));

        $this->assertEquals(2, $h->count());
    }
}
