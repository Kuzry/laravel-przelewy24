<?php

namespace Kuzry\Przelewy24\Api\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Support\Transformation\TransformationContextFactory;

abstract class AbstractSignRequestData extends Data
{
    protected string $sign = '';

    /**
     * @return array<string, string|int|mixed>
     */
    abstract protected function getSignData(): array;

    public function transform(
        null|TransformationContextFactory|TransformationContext $transformationContext = null,
    ): array {
        $data = parent::transform($transformationContext);

        $data['sign'] = hash(
            'sha384',
            (string) json_encode(
                $this->getSignData(),
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            )
        );

        return $data;
    }
}
