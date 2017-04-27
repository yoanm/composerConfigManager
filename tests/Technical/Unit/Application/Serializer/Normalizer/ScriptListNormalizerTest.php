<?php
namespace Technical\Unit\Yoanm\InitRepositoryWithComposer\Application\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\InitRepositoryWithComposer\Application\Serializer\Normalizer\ScriptListNormalizer;
use Yoanm\InitRepositoryWithComposer\Domain\Model\Script;

class ScriptListNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ScriptListNormalizer */
    private $normalizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->normalizer = new ScriptListNormalizer();
    }

    public function testNormalize()
    {
        $list = [];
        $name = 'name';
        $command = 'command';
        $name2 = 'name2';
        $command2 = 'command2';

        /** @var Script|ObjectProphecy $script */
        $script = $this->prophesize(Script::class);
        /** @var Script|ObjectProphecy $script2 */
        $script2 = $this->prophesize(Script::class);

        $list[] = $script->reveal();
        $list[] = $script2->reveal();

        $script->getName()
            ->willReturn($name)
            ->shouldBeCalled();
        $script->getCommand()
            ->willReturn($command)
            ->shouldBeCalled();
        $script2->getName()
            ->willReturn($name2)
            ->shouldBeCalled();
        $script2->getCommand()
            ->willReturn($command2)
            ->shouldBeCalled();

        $expected = [
            $name => [$command],
            $name2 => [$command2],
        ];

        $this->assertSame(
            $expected,
            $this->normalizer->normalize($list)
        );
    }

    public function testNormalizeScriptWithMultiCommand()
    {
        $list = [];
        $name = 'name';
        $command = 'command';
        $command2 = 'command2';

        /** @var Script|ObjectProphecy $script */
        $script = $this->prophesize(Script::class);
        /** @var Script|ObjectProphecy $script2 */
        $script2 = $this->prophesize(Script::class);

        $list[] = $script->reveal();
        $list[] = $script2->reveal();

        $script->getName()
            ->willReturn($name)
            ->shouldBeCalled();
        $script->getCommand()
            ->willReturn($command)
            ->shouldBeCalled();
        $script2->getName()
            ->willReturn($name)
            ->shouldBeCalled();
        $script2->getCommand()
            ->willReturn($command2)
            ->shouldBeCalled();

        $expected = [
            $name => [
                $command,
                $command2,
            ],
        ];

        $this->assertSame(
            $expected,
            $this->normalizer->normalize($list)
        );
    }
}