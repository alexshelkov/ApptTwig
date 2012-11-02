<?php
namespace ApptTwig\Service\Exception;

use InvalidArgumentException as SplException;
use ApptTwig\Service\Exception\ExceptionInterface;

class InvalidArgumentException
    extends SplException
    implements ExceptionInterface
{
}
