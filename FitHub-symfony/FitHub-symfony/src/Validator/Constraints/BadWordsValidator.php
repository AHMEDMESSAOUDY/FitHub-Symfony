<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BadWordsValidator extends ConstraintValidator
{
    private $badWords = ['fuck', 'mot2interdit', 'mot3interdit'];

    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }

        $words = [];
        $commentWords = preg_split('/\s+/', $value);
        foreach ($commentWords as $word) {
            if (in_array(strtolower($word), $this->badWords)) {
                $words[] = $word;
            }
        }

        if (count($words) > 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ words }}', implode(', ', $words))
                ->addViolation();
        }
    }
}
