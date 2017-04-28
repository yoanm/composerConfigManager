<?php
namespace Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Yoanm\ComposerConfigManager\Domain\Model\Author;

class AuthorListNormalizer
{
    const KEY_NAME = 'name';
    const KEY_EMAIL = 'email';
    const KEY_ROLE = 'role';
    /**
     * @param Author[] $authorList
     *
     * @return array
     */
    public function normalize(array $authorList)
    {
        $normalizeList = [];
        foreach ($authorList as $author) {
            $normalizedAuthor = [self::KEY_NAME => $author->getName()];
            if ($author->getEmail()) {
                $normalizedAuthor[self::KEY_EMAIL] = $author->getEmail();
            }
            if ($author->getRole()) {
                $normalizedAuthor[self::KEY_ROLE] = $author->getRole();
            }
            $normalizeList[] = $normalizedAuthor;
        }

        return $normalizeList;
    }
}
