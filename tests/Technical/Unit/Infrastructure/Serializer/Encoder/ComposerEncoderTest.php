<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Infrastructure\Serializer\Encoder;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Encoder\ComposerEncoder as AppComposerEncoder;
use Yoanm\ComposerConfigManager\Infrastructure\Serializer\Encoder\ComposerEncoder;

class ComposerEncoderTest extends \PHPUnit_Framework_TestCase
{
    /** @var AppComposerEncoder|ObjectProphecy */
    private $appComposerEncoder;
    /** @var ComposerEncoder */
    private $encoder;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->appComposerEncoder = $this->prophesize(AppComposerEncoder::class);
        $this->encoder = new ComposerEncoder(
            $this->appComposerEncoder->reveal()
        );
    }

    public function testEncode()
    {
        $data = ['data'];
        $encodedData = 'encoded_data';

        $this->appComposerEncoder->encode($data)
            ->willReturn($encodedData)
            ->shouldBeCalled();

        $this->assertSame(
            $encodedData,
            $this->encoder->encode($data, ComposerEncoder::FORMAT)
        );
    }

    /**
     * @dataProvider getTestSupportsEncodingData
     *
     * @param string $format
     * @param bool   $expectedResult
     */
    public function testSupportsEncoding($format, $expectedResult)
    {
        $this->assertSame(
            $expectedResult,
            $this->encoder->supportsEncoding($format)
        );
    }

    /**
     * @return array
     */
    public function getTestSupportsEncodingData()
    {
        return [
            'composer' => [
                'format' => ComposerEncoder::FORMAT,
                'expectedResult' => true
            ],
            'other' => [
                'format' => 'json',
                'expectedResult' => false
            ],
        ];
    }
}
