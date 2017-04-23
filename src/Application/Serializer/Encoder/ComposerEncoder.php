<?php
namespace Yoanm\InitRepositoryWithComposer\Application\Serializer\Encoder;

class ComposerEncoder
{
    public function encode($data)
    {
        $data = json_encode($data, JSON_PRETTY_PRINT);

        if ('' != trim($data)) {
            $data .= "\n";
        }

        return $data;
    }
}