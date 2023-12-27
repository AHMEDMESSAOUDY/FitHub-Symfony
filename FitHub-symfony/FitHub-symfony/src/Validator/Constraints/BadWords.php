<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BadWords extends Constraint
{
    public $message = 'Le commentaire contient des mots interdits : {{ words }}';
}
