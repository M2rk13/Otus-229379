<?php

declare(strict_types=1);

namespace App\MathematicalFunctions;

use App\Exception\ImpossibleDiscriminantValueException;
use App\Exception\SeniorCoefficientException;

final class QuadraticEquation
{
    private const MAX_SCALE = 14;

    /**
     * @return float[]
     *
     * @throws ImpossibleDiscriminantValueException
     * @throws SeniorCoefficientException
     */
    public static function execute(float $a, float $b = 0, float $c = 0): array
    {
        $aStr = self::getNumberFormatted($a);
        $bStr = self::getNumberFormatted($b);
        $cStr = self::getNumberFormatted($c);

        if (bccomp($aStr, '0', self::MAX_SCALE) === 0) {
            throw new SeniorCoefficientException('value can\'t be equal 0');
        }

        $disc = bcsub(
            bcmul(
                $bStr,
                $bStr,
                self::MAX_SCALE
            ),
            bcmul(
                '4',
                bcmul(
                    $aStr,
                    $cStr,
                    self::MAX_SCALE
                )
            )
        );

        return match(bccomp($disc, '0')) {
            -1 => [],
            0 => [
                (float) bcdiv(
                    $bStr,
                    bcmul(
                        '2',
                        $aStr,
                        self::MAX_SCALE
                    ),
                    self::MAX_SCALE
                ),
            ],
            1 => [
                (float) bcdiv(
                    bcsub(
                        $bStr,
                        bcsqrt(
                            $disc,
                            self::MAX_SCALE
                        ),
                        self::MAX_SCALE
                    ),
                    bcmul(
                        '2',
                        $aStr,
                        self::MAX_SCALE
                    ),
                    self::MAX_SCALE
                ),
                (float) bcdiv(
                    bcadd($bStr,
                        bcsqrt(
                            $disc,
                            self::MAX_SCALE
                        ),
                        self::MAX_SCALE
                    ),
                    bcmul(
                        '2',
                        $aStr,
                        self::MAX_SCALE
                    ),
                    self::MAX_SCALE
                ),
            ],
            default => throw new ImpossibleDiscriminantValueException($disc),
        };
    }

    private static function getNumberFormatted(float $value): string
    {
         $integerValuePart = number_format($value);
         $integerLengthPart = strlen($integerValuePart);

        return number_format($value, self::MAX_SCALE - $integerLengthPart, '.', '');
    }
}
