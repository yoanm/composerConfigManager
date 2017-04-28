<?php
namespace Technical\Unit\Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\AuthorListNormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\Author;

class AuthorListNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AuthorListNormalizer */
    private $normalizer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->normalizer = new AuthorListNormalizer();
    }

    public function testNormalize()
    {
        $list = [];
        $name = 'name';
        $email = 'email';
        $role = 'role';

        /** @var Author|ObjectProphecy $author */
        $author = $this->prophesize(Author::class);

        $list[] = $author->reveal();

        $author->getName()
            ->willReturn($name)
            ->shouldBeCalled();
        $author->getEmail()
            ->willReturn($email)
            ->shouldBeCalled();
        $author->getRole()
            ->willReturn($role)
            ->shouldBeCalled();

        $expected = [
            [
                AuthorListNormalizer::KEY_NAME => $name,
                AuthorListNormalizer::KEY_EMAIL => $email,
                AuthorListNormalizer::KEY_ROLE => $role,
            ],
        ];

        $this->assertSame(
            $expected,
            $this->normalizer->normalize($list)
        );
    }

    public function testNormalizeNameWithOptionalProperties()
    {
        $list = [];
        // Name only
        $name1 = 'name1';
        // Name + email
        $name2 = 'name2';
        $email2 = 'email2';
        // Name + role
        $name3 = 'name3';
        $role3 = 'role3';
        // All properties
        $name4 = 'name4';
        $email4 = 'email4';
        $role4 = 'role4';

        /** @var Author|ObjectProphecy $author1 */
        $author1 = $this->prophesize(Author::class);
        /** @var Author|ObjectProphecy $author2 */
        $author2 = $this->prophesize(Author::class);
        /** @var Author|ObjectProphecy $author3 */
        $author3 = $this->prophesize(Author::class);
        /** @var Author|ObjectProphecy $author4 */
        $author4 = $this->prophesize(Author::class);

        $list[] = $author1->reveal();
        $list[] = $author2->reveal();
        $list[] = $author3->reveal();
        $list[] = $author4->reveal();

        $author1->getName()
            ->willReturn($name1)
            ->shouldBeCalled();
        $author1->getEmail()
            ->shouldBeCalled();
        $author1->getRole()
            ->shouldBeCalled();
        $author2->getName()
            ->willReturn($name2)
            ->shouldBeCalled();
        $author2->getEmail()
            ->willReturn($email2)
            ->shouldBeCalled();
        $author2->getRole()
            ->shouldBeCalled();
        $author3->getName()
            ->willReturn($name3)
            ->shouldBeCalled();
        $author3->getEmail()
            ->shouldBeCalled();
        $author3->getRole()
            ->willReturn($role3)
            ->shouldBeCalled();
        $author4->getName()
            ->willReturn($name4)
            ->shouldBeCalled();
        $author4->getEmail()
            ->willReturn($email4)
            ->shouldBeCalled();
        $author4->getRole()
            ->willReturn($role4)
            ->shouldBeCalled();

        $expected = [
            [
                AuthorListNormalizer::KEY_NAME => $name1,
            ],
            [
                AuthorListNormalizer::KEY_NAME => $name2,
                AuthorListNormalizer::KEY_EMAIL => $email2,
            ],
            [
                AuthorListNormalizer::KEY_NAME => $name3,
                AuthorListNormalizer::KEY_ROLE => $role3,
            ],
            [
                AuthorListNormalizer::KEY_NAME => $name4,
                AuthorListNormalizer::KEY_EMAIL => $email4,
                AuthorListNormalizer::KEY_ROLE => $role4,
            ],
        ];

        $this->assertSame(
            $expected,
            $this->normalizer->normalize($list)
        );
    }
}
