<?php
namespace ApptTwig\Exception;

use InvalidArgumentException as SplException;
use ApptTwig\Exception\ExceptionInterface;

class InvalidArgumentException
    extends SplException
    implements ExceptionInterface
{
}
