<?php
namespace ApptTwig\Exception;

use DomainException as SplException;
use ApptTwig\Exception\ExceptionInterface;

class DomainException
    extends SplException
    implements ExceptionInterface
{
}
