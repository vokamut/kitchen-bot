<?php

declare(strict_types=1);

namespace App\Models;

enum CategoryEnum: int
{
    case NONE = 0;
    case BREAKFASTS = 1;
    case FIRST_COURSES = 2;
    case MAIN_COURSES = 3;
    case SIDE_DISHES = 4;
    case APPETIZERS = 5;
    case PREPARATIONS = 6;
    case SALADS = 7;
    case SAUCES = 8;
    case DESSERTS = 9;
    case BAKED_GOODS = 10;

    public const string NONE_LABEL = 'Без категории';
    public const string BREAKFASTS_LABEL = 'Завтраки';
    public const string FIRST_COURSES_LABEL = 'Первые блюда';
    public const string MAIN_COURSES_LABEL = 'Вторые блюда';
    public const string SIDE_DISHES_LABEL = 'Гарниры';
    public const string APPETIZERS_LABEL = 'Закуски';
    public const string PREPARATIONS_LABEL = 'Заготовки';
    public const string SALADS_LABEL = 'Салаты';
    public const string SAUCES_LABEL = 'Соусы';
    public const string DESSERTS_LABEL = 'Десерты';
    public const string BAKED_GOODS_LABEL = 'Выпечка';

    public static function labels(): array
    {
        return [
            self::NONE->value => self::NONE_LABEL,
            self::BREAKFASTS->value => self::BREAKFASTS_LABEL,
            self::FIRST_COURSES->value => self::FIRST_COURSES_LABEL,
            self::MAIN_COURSES->value => self::MAIN_COURSES_LABEL,
            self::SIDE_DISHES->value => self::SIDE_DISHES_LABEL,
            self::APPETIZERS->value => self::APPETIZERS_LABEL,
            self::PREPARATIONS->value => self::PREPARATIONS_LABEL,
            self::SALADS->value => self::SALADS_LABEL,
            self::SAUCES->value => self::SAUCES_LABEL,
            self::DESSERTS->value => self::DESSERTS_LABEL,
            self::BAKED_GOODS->value => self::BAKED_GOODS_LABEL,
        ];
    }

    public static function getValueByLabel(string $label): int
    {
        return match ($label) {
            self::NONE_LABEL => self::NONE->value,
            self::BREAKFASTS_LABEL => self::BREAKFASTS->value,
            self::FIRST_COURSES_LABEL => self::FIRST_COURSES->value,
            self::MAIN_COURSES_LABEL => self::MAIN_COURSES->value,
            self::SIDE_DISHES_LABEL => self::SIDE_DISHES->value,
            self::APPETIZERS_LABEL => self::APPETIZERS->value,
            self::PREPARATIONS_LABEL => self::PREPARATIONS->value,
            self::SALADS_LABEL => self::SALADS->value,
            self::SAUCES_LABEL => self::SAUCES->value,
            self::DESSERTS_LABEL => self::DESSERTS->value,
            self::BAKED_GOODS_LABEL => self::BAKED_GOODS->value,
        };
    }

    public static function getLabelByValue(int $value): string
    {
        return match ($value) {
            self::NONE->value => self::NONE_LABEL,
            self::BREAKFASTS->value => self::BREAKFASTS_LABEL,
            self::FIRST_COURSES->value => self::FIRST_COURSES_LABEL,
            self::MAIN_COURSES->value => self::MAIN_COURSES_LABEL,
            self::SIDE_DISHES->value => self::SIDE_DISHES_LABEL,
            self::APPETIZERS->value => self::APPETIZERS_LABEL,
            self::PREPARATIONS->value => self::PREPARATIONS_LABEL,
            self::SALADS->value => self::SALADS_LABEL,
            self::SAUCES->value => self::SAUCES_LABEL,
            self::DESSERTS->value => self::DESSERTS_LABEL,
            self::BAKED_GOODS->value => self::BAKED_GOODS_LABEL,
        };
    }

    public static function getLabelByCase(self $case): string
    {
        return match ($case) {
            self::NONE => self::NONE_LABEL,
            self::BREAKFASTS => self::BREAKFASTS_LABEL,
            self::FIRST_COURSES => self::FIRST_COURSES_LABEL,
            self::MAIN_COURSES => self::MAIN_COURSES_LABEL,
            self::SIDE_DISHES => self::SIDE_DISHES_LABEL,
            self::APPETIZERS => self::APPETIZERS_LABEL,
            self::PREPARATIONS => self::PREPARATIONS_LABEL,
            self::SALADS => self::SALADS_LABEL,
            self::SAUCES => self::SAUCES_LABEL,
            self::DESSERTS => self::DESSERTS_LABEL,
            self::BAKED_GOODS => self::BAKED_GOODS_LABEL,
        };
    }

    public static function getCaseByValue(int $value): self
    {
        return match ($value) {
            self::NONE->value => self::NONE,
            self::BREAKFASTS->value => self::BREAKFASTS,
            self::FIRST_COURSES->value => self::FIRST_COURSES,
            self::MAIN_COURSES->value => self::MAIN_COURSES,
            self::SIDE_DISHES->value => self::SIDE_DISHES,
            self::APPETIZERS->value => self::APPETIZERS,
            self::PREPARATIONS->value => self::PREPARATIONS,
            self::SALADS->value => self::SALADS,
            self::SAUCES->value => self::SAUCES,
            self::DESSERTS->value => self::DESSERTS,
            self::BAKED_GOODS->value => self::BAKED_GOODS,
        };
    }
}
