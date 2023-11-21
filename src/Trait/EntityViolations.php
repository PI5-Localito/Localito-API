<?php

namespace App\Trait;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

trait EntityViolations
{
    /**
     * Parse the violations as json array
     *
     * @param ConstraintViolationListInterface $list
     * @return array<int, string>
     */
    protected function processErrors(ConstraintViolationListInterface $list): array
    {
        if ($list->count() == 0) {
            return [];
        }

        $violations = [];
        foreach($list as $violation) {
            $violations[] = [
                'message' => $violation->getMessage(),
                'cause' => $violation->getPropertyPath(),
            ];
        }

        throw new BadRequestException(json_encode($violations));
    }

}
