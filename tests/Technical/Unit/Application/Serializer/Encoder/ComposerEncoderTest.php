<?php
namespace Technical\Unit\Yoanm\InitRepositoryWithComposer\Application\Serializer\Encoder;

use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Yoanm\InitRepositoryWithComposer\Application\Serializer\Encoder\ComposerEncoder;

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
}
