<?php
namespace Yoanm\InitRepositoryWithComposer\Application\Serializer\Normalizer;

use Yoanm\InitRepositoryWithComposer\Domain\Model\Author;

class AuthorListNormalizer
{
    /**
     * @param Author[] $authorList
     *
     * @return array
     */
    public function normalize(array $authorList)
    {
        $normalizeList = [];
        foreach ($authorList as $author) {
            $normalizedAuthor = ['name' => $author->getName()];
            if ($author->getEmail()) {
                $normalizedAuthor['email'] = $author->getEmail();
            }
            if ($author->getRole()) {
                $normalizedAuthor['role'] = $author->getRole();
            }
            $normalizeList[] = $normalizedAuthor;
        }

        return $normalizeList;
    }
}