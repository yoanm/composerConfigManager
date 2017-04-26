<?php
namespace Technical\Unit\Yoanm\InitRepositoryWithComposer\Application\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\InitRepositoryWithComposer\Application\Serializer\Normalizer\SupportListNormalizer;
use Yoanm\InitRepositoryWithComposer\Domain\Model\Support;

class SupportListNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var SupportListNormalizer */
    private $normalizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->normalizer = new SupportListNormalizer();
    }

    public function testNormalize()
    {
        $list = [];
        $type = 'type';
        $url = 'url';

        /** @var Support|ObjectProphecy $support */
        $support = $this->prophesize(Support::class);

        $list[] = $support->reveal();

        $support->getType()
            ->willReturn($type)
            ->shouldBeCalled();

        $support->getUrl()
            ->willReturn($url)
            ->shouldBeCalled();

        $expected = [
            $type => $url
        ];

        $this->assertSame(
            $expected,
            $this->normalizer->normalize($list)
        );
    }
}
