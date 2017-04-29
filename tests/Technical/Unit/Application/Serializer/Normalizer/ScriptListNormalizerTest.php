<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ScriptListNormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\Script;

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

    public function testDenormalize()
    {
        $name = 'name';
        $command = 'command';
        $name2 = 'name2';
        $command2 = 'command2';
        $command3 = 'command3';

        $list = [
            $name => [$command, $command3],
            $name2 => [$command2],
        ];

        $denormalizedList = $this->normalizer->denormalize($list);

        $this->assertContainsOnlyInstancesOf(Script::class, $denormalizedList);
        $this->assertCount(3, $denormalizedList);

        $script = array_shift($denormalizedList);
        $this->assertSame($name, $script->getName());
        $this->assertSame($command, $script->getCommand());

        $script = array_shift($denormalizedList);
        $this->assertSame($name, $script->getName());
        $this->assertSame($command3, $script->getCommand());

        $script = array_shift($denormalizedList);
        $this->assertSame($name2, $script->getName());
        $this->assertSame($command2, $script->getCommand());
    }
}
