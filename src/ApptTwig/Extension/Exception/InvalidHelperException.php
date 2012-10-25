<?php
namespace ApptTwig\Extension\Exception;

use DomainException;
use ApptTwig\Extension\Exception\ExceptionInterface;

class InvalidHelperException
    extends DomainException
    implements ExceptionInterface
{
}
