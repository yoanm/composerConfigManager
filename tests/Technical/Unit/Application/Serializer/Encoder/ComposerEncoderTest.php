<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Serializer\Encoder;

use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Yoanm\ComposerConfigManager\Application\Serializer\Encoder\ComposerEncoder;

class ComposerEncoderTest extends \PHPUnit_Framework_TestCase
{
    /** @var ComposerEncoder */
    private $encoder;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->encoder = new ComposerEncoder();
    }

    public function testEncode()
    {
        $this->assertSame(
            <<<EXPECTED
{
    "a": "b"
}

EXPECTED
            ,
            $this->encoder->encode(
                [
                    'a' => 'b'
                ]
            )
        );
    }

    public function testDecode()
    {
        $this->assertSame(
            [
                'a' => 'b'
            ],
            $this->encoder->decode(
                <<<ENCODED
{
    "a": "b"
}
ENCODED
            )
        );
    }

    /**
     * @return array
     */
    public function testEncodeHandleFailure()
    {
        $objectA = new \stdClass();
        $objectB = new \stdClass();
        // Create a recursion;
        $objectA->b = $objectB;
        $objectB->a = $objectA;

        $this->setExpectedException(UnexpectedValueException::class);

        $this->encoder->encode([$objectA, $objectB]);
    }

    /**
     * @return array
     */
    public function testDecodeHandleFailure()
    {
        // Add a ',' to throw a syntax error exception
        $encoded = <<<ENCODED
{
    "a": "b",
}
ENCODED;

        $this->setExpectedException(UnexpectedValueException::class);

        $this->encoder->decode($encoded);
    }
}
