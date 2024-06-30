<?php

namespace App\Enums;

enum PriceRange: string {
    case RANGE_0_1500 = '0-1500';
    case RANGE_1500_5000 = '1500-5000';
    case RANGE_5000_10000 = '5000-10000';
    case RANGE_10000_30000 = '10000-30000';
    case RANGE_30000 = '30000-';

    public function values(): array {
        return match($this) {
            self::RANGE_0_1500 => [0, 1500],
            self::RANGE_1500_5000 => [1500, 5000],
            self::RANGE_5000_10000 => [5000, 10000],
            self::RANGE_10000_30000 => [10000, 30000],
            self::RANGE_30000 => [30000, null],
        };
    }

    public static function fromRequestKey(string $key): ?PriceRange {
        switch ($key) {
            case '0-1500':
                return self::RANGE_0_1500;
            case '1500-5000':
                return self::RANGE_1500_5000;
            case '5000-10000':
                return self::RANGE_5000_10000;
            case '10000-30000':
                return self::RANGE_10000_30000;
            case '30000-':
                return self::RANGE_30000;
            default:
                return null;
        }
    }
}

