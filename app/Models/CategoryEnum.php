<?php

declare(strict_types=1);

namespace App\Models;

enum CategoryEnum: int
{
    case BREAKFASTS = 0;
    case FIRST_COURSES = 1;
    case MAIN_COURSES = 2;
    case SIDE_DISHES = 3;
    case APPETIZERS = 4;
    case PREPARATIONS = 5;
    case SALADS = 6;
    case SAUCES = 7;
    case DESSERTS = 8;
    case BAKED_GOODS = 9;

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
}
