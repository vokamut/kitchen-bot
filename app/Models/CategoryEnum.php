<?php

declare(strict_types=1);

namespace App\Models;

enum CategoryEnum: int
{
    case BREAKFASTS = 1;
    case FIRST_COURSES = 2;
    case MAIN_COURSES = 3;
    case SIDE_DISHES = 4;
    case SALADS = 5;
    case APPETIZERS = 6;
    case BAKED_GOODS = 7;
    case DESSERTS = 8;
    case PREPARATIONS = 9;
    case SAUCES = 10;
    case NONE = 0;

    public const string BREAKFASTS_LABEL = 'Завтраки';

    public const string FIRST_COURSES_LABEL = 'Первые блюда';

    public const string MAIN_COURSES_LABEL = 'Вторые блюда';

    public const string SIDE_DISHES_LABEL = 'Гарниры';

    public const string SALADS_LABEL = 'Салаты';

    public const string APPETIZERS_LABEL = 'Закуски';

    public const string BAKED_GOODS_LABEL = 'Выпечка';

    public const string DESSERTS_LABEL = 'Десерты';

    public const string PREPARATIONS_LABEL = 'Заготовки';

    public const string SAUCES_LABEL = 'Соусы';

    public const string NONE_LABEL = 'Без категории';

    public static function labels(): array
    {
        return [
            self::BREAKFASTS->value => self::BREAKFASTS_LABEL,
            self::FIRST_COURSES->value => self::FIRST_COURSES_LABEL,
            self::MAIN_COURSES->value => self::MAIN_COURSES_LABEL,
            self::SIDE_DISHES->value => self::SIDE_DISHES_LABEL,
            self::SALADS->value => self::SALADS_LABEL,
            self::APPETIZERS->value => self::APPETIZERS_LABEL,
            self::BAKED_GOODS->value => self::BAKED_GOODS_LABEL,
            self::DESSERTS->value => self::DESSERTS_LABEL,
            self::PREPARATIONS->value => self::PREPARATIONS_LABEL,
            self::SAUCES->value => self::SAUCES_LABEL,
            self::NONE->value => self::NONE_LABEL,
        ];
    }

    public static function getValueByLabel(string $label): int
    {
        return match ($label) {
            self::BREAKFASTS_LABEL => self::BREAKFASTS->value,
            self::FIRST_COURSES_LABEL => self::FIRST_COURSES->value,
            self::MAIN_COURSES_LABEL => self::MAIN_COURSES->value,
            self::SIDE_DISHES_LABEL => self::SIDE_DISHES->value,
            self::SALADS_LABEL => self::SALADS->value,
            self::APPETIZERS_LABEL => self::APPETIZERS->value,
            self::BAKED_GOODS_LABEL => self::BAKED_GOODS->value,
            self::DESSERTS_LABEL => self::DESSERTS->value,
            self::PREPARATIONS_LABEL => self::PREPARATIONS->value,
            self::SAUCES_LABEL => self::SAUCES->value,
            self::NONE_LABEL => self::NONE->value,
        };
    }

    public static function getLabelByValue(int $value): string
    {
        return match ($value) {
            self::BREAKFASTS->value => self::BREAKFASTS_LABEL,
            self::FIRST_COURSES->value => self::FIRST_COURSES_LABEL,
            self::MAIN_COURSES->value => self::MAIN_COURSES_LABEL,
            self::SIDE_DISHES->value => self::SIDE_DISHES_LABEL,
            self::SALADS->value => self::SALADS_LABEL,
            self::APPETIZERS->value => self::APPETIZERS_LABEL,
            self::BAKED_GOODS->value => self::BAKED_GOODS_LABEL,
            self::DESSERTS->value => self::DESSERTS_LABEL,
            self::PREPARATIONS->value => self::PREPARATIONS_LABEL,
            self::SAUCES->value => self::SAUCES_LABEL,
            self::NONE->value => self::NONE_LABEL,
        };
    }

    public static function getLabelByCase(self $case): string
    {
        return match ($case) {
            self::BREAKFASTS => self::BREAKFASTS_LABEL,
            self::FIRST_COURSES => self::FIRST_COURSES_LABEL,
            self::MAIN_COURSES => self::MAIN_COURSES_LABEL,
            self::SIDE_DISHES => self::SIDE_DISHES_LABEL,
            self::SALADS => self::SALADS_LABEL,
            self::APPETIZERS => self::APPETIZERS_LABEL,
            self::BAKED_GOODS => self::BAKED_GOODS_LABEL,
            self::DESSERTS => self::DESSERTS_LABEL,
            self::PREPARATIONS => self::PREPARATIONS_LABEL,
            self::SAUCES => self::SAUCES_LABEL,
            self::NONE => self::NONE_LABEL,
        };
    }

    public static function getCaseByValue(int $value): self
    {
        return match ($value) {
            self::BREAKFASTS->value => self::BREAKFASTS,
            self::FIRST_COURSES->value => self::FIRST_COURSES,
            self::MAIN_COURSES->value => self::MAIN_COURSES,
            self::SIDE_DISHES->value => self::SIDE_DISHES,
            self::SALADS->value => self::SALADS,
            self::APPETIZERS->value => self::APPETIZERS,
            self::BAKED_GOODS->value => self::BAKED_GOODS,
            self::DESSERTS->value => self::DESSERTS,
            self::PREPARATIONS->value => self::PREPARATIONS,
            self::SAUCES->value => self::SAUCES,
            self::NONE->value => self::NONE,
        };
    }

    public static function getCaseByLabel(string $label): self
    {
        return match ($label) {
            self::BREAKFASTS_LABEL => self::BREAKFASTS,
            self::FIRST_COURSES_LABEL => self::FIRST_COURSES,
            self::MAIN_COURSES_LABEL => self::MAIN_COURSES,
            self::SIDE_DISHES_LABEL => self::SIDE_DISHES,
            self::SALADS_LABEL => self::SALADS,
            self::APPETIZERS_LABEL => self::APPETIZERS,
            self::BAKED_GOODS_LABEL => self::BAKED_GOODS,
            self::DESSERTS_LABEL => self::DESSERTS,
            self::PREPARATIONS_LABEL => self::PREPARATIONS,
            self::SAUCES_LABEL => self::SAUCES,
            self::NONE_LABEL => self::NONE,
        };
    }
}
