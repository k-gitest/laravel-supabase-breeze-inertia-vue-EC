<?php

namespace App\Enums;

enum SortOption: string {
    case PRICE_ASC = 'price_asc';
    case PRICE_DESC = 'price_desc';
    case FAVORITES_ASC = 'favorites_asc';
    case FAVORITES_DESC = 'favorites_desc';
    case NEWEST = 'newest';

    public function values(): array {
        return match($this) {
            self::PRICE_ASC => ['price_excluding_tax', 'asc'],
            self::PRICE_DESC => ['price_excluding_tax', 'desc'],
            self::FAVORITES_ASC => ['favorites_count', 'asc'],
            self::FAVORITES_DESC => ['favorite_count', 'desc'],
            self::NEWEST => ['created_at', 'desc'],
        };
    }
}

