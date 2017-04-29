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

    public function testDenormalize()
    {
        $list = [];
        $name = 'name';
        $email = 'email';
        $role = 'role';

        $list[] = [
            'name' => $name,
            'email' => $email,
            'role' => $role,
        ];

        $normalizedList = $this->normalizer->denormalize($list);

        $this->assertContainsOnlyInstancesOf(Author::class, $normalizedList);
        $this->assertCount(count($list), $normalizedList);
        $author = array_shift($normalizedList);
        $this->assertSame($name, $author->getName());
        $this->assertSame($email, $author->getEmail());
        $this->assertSame($role, $author->getRole());
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

        $list = [
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

        $normalizedList = $this->normalizer->denormalize($list);

        $this->assertContainsOnlyInstancesOf(Author::class, $normalizedList);
        $this->assertCount(count($list), $normalizedList);

        $author = array_shift($normalizedList);
        $this->assertSame($name1, $author->getName());
        $this->assertSame(null, $author->getEmail());
        $this->assertSame(null, $author->getRole());

        $author = array_shift($normalizedList);
        $this->assertSame($name2, $author->getName());
        $this->assertSame($email2, $author->getEmail());
        $this->assertSame(null, $author->getRole());

        $author = array_shift($normalizedList);
        $this->assertSame($name3, $author->getName());
        $this->assertSame(null, $author->getEmail());
        $this->assertSame($role3, $author->getRole());

        $author = array_shift($normalizedList);
        $this->assertSame($name4, $author->getName());
        $this->assertSame($email4, $author->getEmail());
        $this->assertSame($role4, $author->getRole());
    }
}
