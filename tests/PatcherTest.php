<?php

namespace PatchApply;

use PHPUnit\Framework\TestCase;

/**
 * @covers \PatchApply\Patcher
 */
class PatcherTest extends TestCase
{
    /**
     * @var Patcher
     */
    private Patcher $patcher;

    public function setUp(): void
    {
        $this->patcher = new Patcher();
    }

    /**
     * @param string $input
     * @param string $patch
     * @param string $expected
     *
     * @dataProvider patchProvider
     */
    public function testApply(string $input, string $patch, string $expected): void
    {
        $output = $this->patcher->apply($input, $patch);

        $this->assertEquals($expected, $output);
    }

    /**
     * @return string[][]
     */
    public function patchProvider(): array
    {
        return [
            [
                "abc",
                <<<PATCH
                --- a	2020-10-10 15:11:50.720235698 +0100
                +++ b	2020-10-10 15:12:02.414511742 +0100
                @@ -1 +1 @@
                -abc
                +xyz
                
                PATCH,
                "xyz",
            ],
            [
                "abc\ndef",
                <<<PATCH
                --- a	2020-10-10 14:26:30.381951580 +0100
                +++ b	2020-10-10 14:26:42.361963579 +0100
                @@ -1,2 +1,2 @@
                 abc
                -def
                +xyz
                
                PATCH,
                "abc\nxyz",
            ],
            [
                <<<INPUT
                Hello
                World
                
                This
                Is
                My
                Message
                INPUT,
                <<<DIFF
                --- a	2020-10-10 15:16:28.202212469 +0100
                +++ b	2020-10-10 15:16:50.347090122 +0100
                @@ -1,7 +1,10 @@
                 Hello
                +There
                 World
                 
                 This
                 Is
                 My
                +Long
                +Long
                 Message
                DIFF,
                <<<EXPECTED
                Hello
                There
                World
                
                This
                Is
                My
                Long
                Long
                Message
                EXPECTED,
            ],
            [
                <<<INPUT
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Mauris a ligula a felis pharetra laoreet.
                Duis aliquam tortor facilisis, porta enim eu, feugiat mauris.
                
                In consequat lorem ut mi pulvinar, eu eleifend purus interdum.
                Nulla et arcu eget mauris euismod accumsan non sit amet quam.
                Nulla eu orci at mi ultrices tincidunt vel eu quam.
                Nunc mollis orci vel urna varius, vel ullamcorper lacus interdum.
                
                Sed molestie ligula et sapien rutrum rhoncus.
                Aliquam cursus velit ut velit dictum commodo.
                Curabitur in mi sit amet velit lobortis bibendum vitae sit amet mi.
                Curabitur at ipsum ac mi luctus gravida nec accumsan sem.
                Suspendisse mattis velit nec odio aliquam pretium.
                
                Pellentesque non odio imperdiet, auctor erat sit amet, auctor ex.
                Praesent sit amet libero bibendum dui accumsan egestas ac at sem.
                Pellentesque non velit facilisis ipsum porttitor congue a eu mi.
                Proin maximus lectus at ligula gravida ultrices.
                Fusce viverra nisi id lobortis elementum.
                Pellentesque venenatis sapien nec urna blandit, a accumsan magna tristique.
                
                Aenean ac nisi id massa egestas ultrices a sed orci.
                Nullam feugiat nisl vitae laoreet pellentesque.
                Curabitur molestie lectus et urna faucibus rutrum.
                Etiam fringilla mauris vitae porttitor ultrices.
                
                INPUT,
                <<<DIFF
                --- a	2020-10-10 16:10:40.173046587 +0100
                +++ b	2020-10-10 16:10:58.126875126 +0100
                @@ -3,7 +3,6 @@
                 Duis aliquam tortor facilisis, porta enim eu, feugiat mauris.
                 
                 In consequat lorem ut mi pulvinar, eu eleifend purus interdum.
                -Nulla et arcu eget mauris euismod accumsan non sit amet quam.
                 Nulla eu orci at mi ultrices tincidunt vel eu quam.
                 Nunc mollis orci vel urna varius, vel ullamcorper lacus interdum.
                 
                @@ -20,6 +19,7 @@
                 Fusce viverra nisi id lobortis elementum.
                 Pellentesque venenatis sapien nec urna blandit, a accumsan magna tristique.
                 
                +Nulla et arcu eget mauris euismod accumsan non sit amet quam.
                 Aenean ac nisi id massa egestas ultrices a sed orci.
                 Nullam feugiat nisl vitae laoreet pellentesque.
                 Curabitur molestie lectus et urna faucibus rutrum.
                
                DIFF,
                <<<EXPECTED
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Mauris a ligula a felis pharetra laoreet.
                Duis aliquam tortor facilisis, porta enim eu, feugiat mauris.
                
                In consequat lorem ut mi pulvinar, eu eleifend purus interdum.
                Nulla eu orci at mi ultrices tincidunt vel eu quam.
                Nunc mollis orci vel urna varius, vel ullamcorper lacus interdum.
                
                Sed molestie ligula et sapien rutrum rhoncus.
                Aliquam cursus velit ut velit dictum commodo.
                Curabitur in mi sit amet velit lobortis bibendum vitae sit amet mi.
                Curabitur at ipsum ac mi luctus gravida nec accumsan sem.
                Suspendisse mattis velit nec odio aliquam pretium.
                
                Pellentesque non odio imperdiet, auctor erat sit amet, auctor ex.
                Praesent sit amet libero bibendum dui accumsan egestas ac at sem.
                Pellentesque non velit facilisis ipsum porttitor congue a eu mi.
                Proin maximus lectus at ligula gravida ultrices.
                Fusce viverra nisi id lobortis elementum.
                Pellentesque venenatis sapien nec urna blandit, a accumsan magna tristique.
                
                Nulla et arcu eget mauris euismod accumsan non sit amet quam.
                Aenean ac nisi id massa egestas ultrices a sed orci.
                Nullam feugiat nisl vitae laoreet pellentesque.
                Curabitur molestie lectus et urna faucibus rutrum.
                Etiam fringilla mauris vitae porttitor ultrices.
                
                EXPECTED,
            ],
        ];
    }
}
