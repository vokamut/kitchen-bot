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

    case FIRST_COURSES_CHICKEN = 11;
    case FIRST_COURSES_PORK = 12;
    case FIRST_COURSES_BEEF = 13;
    case FIRST_COURSES_FISH = 14;
    case FIRST_COURSES_VEGETABLES = 15;

    case MAIN_COURSES_CHICKEN = 21;
    case MAIN_COURSES_PORK = 22;
    case MAIN_COURSES_BEEF = 23;
    case MAIN_COURSES_FISH = 24;
    case MAIN_COURSES_OFFAL = 25;

    case SALADS_CHICKEN = 51;
    case SALADS_PORK = 52;
    case SALADS_BEEF = 53;
    case SALADS_FISH = 54;

    case PREPARATIONS_BERRIES_FRUITS = 91;
    case PREPARATIONS_VEGETABLES = 92;

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

    public const string  FIRST_COURSES_CHICKEN_LABEL = 'Курица';

    public const string  FIRST_COURSES_PORK_LABEL = 'Свинина';

    public const string  FIRST_COURSES_BEEF_LABEL = 'Говядина';

    public const string  FIRST_COURSES_FISH_LABEL = 'Рыба';

    public const string  FIRST_COURSES_VEGETABLES_LABEL = 'Овощной';

    public const string  MAIN_COURSES_CHICKEN_LABEL = 'Курица';

    public const string  MAIN_COURSES_PORK_LABEL = 'Свинина';

    public const string  MAIN_COURSES_BEEF_LABEL = 'Говядина';

    public const string  MAIN_COURSES_FISH_LABEL = 'Рыба';

    public const string  MAIN_COURSES_OFFAL_LABEL = 'Субпродукты';

    public const string  SALADS_CHICKEN_LABEL = 'Курица';

    public const string  SALADS_PORK_LABEL = 'Свинина';

    public const string  SALADS_BEEF_LABEL = 'Говядина';

    public const string  SALADS_FISH_LABEL = 'Рыба';

    public const string  PREPARATIONS_BERRIES_FRUITS_LABEL = 'Ягоды/фрукты';

    public const string  PREPARATIONS_VEGETABLES_LABEL = 'Овощи';

    public static function parentLabels(): array
    {
        return [
            self::FIRST_COURSES->value => self::FIRST_COURSES_LABEL,
            self::MAIN_COURSES->value => self::MAIN_COURSES_LABEL,
            self::SALADS->value => self::SALADS_LABEL,
            self::PREPARATIONS->value => self::PREPARATIONS_LABEL,
        ];
    }

    public static function labels(?int $parentCategory = null): array
    {
        if ($parentCategory === self::FIRST_COURSES->value) {
            return [
                self::FIRST_COURSES_CHICKEN->value => self::FIRST_COURSES_CHICKEN_LABEL,
                self::FIRST_COURSES_PORK->value => self::FIRST_COURSES_PORK_LABEL,
                self::FIRST_COURSES_BEEF->value => self::FIRST_COURSES_BEEF_LABEL,
                self::FIRST_COURSES_FISH->value => self::FIRST_COURSES_FISH_LABEL,
                self::FIRST_COURSES_VEGETABLES->value => self::FIRST_COURSES_VEGETABLES_LABEL,
            ];
        }

        if ($parentCategory === self::MAIN_COURSES->value) {
            return [
                self::MAIN_COURSES_CHICKEN->value => self::MAIN_COURSES_CHICKEN_LABEL,
                self::MAIN_COURSES_PORK->value => self::MAIN_COURSES_PORK_LABEL,
                self::MAIN_COURSES_BEEF->value => self::MAIN_COURSES_BEEF_LABEL,
                self::MAIN_COURSES_FISH->value => self::MAIN_COURSES_FISH_LABEL,
                self::MAIN_COURSES_OFFAL->value => self::MAIN_COURSES_OFFAL_LABEL,
            ];
        }

        if ($parentCategory === self::SALADS->value) {
            return [
                self::SALADS_CHICKEN->value => self::SALADS_CHICKEN_LABEL,
                self::SALADS_PORK->value => self::SALADS_PORK_LABEL,
                self::SALADS_BEEF->value => self::SALADS_BEEF_LABEL,
                self::SALADS_FISH->value => self::SALADS_FISH_LABEL,
            ];
        }

        if ($parentCategory === self::PREPARATIONS->value) {
            return [
                self::PREPARATIONS_BERRIES_FRUITS->value => self::PREPARATIONS_BERRIES_FRUITS_LABEL,
                self::PREPARATIONS_VEGETABLES->value => self::PREPARATIONS_VEGETABLES_LABEL,
            ];
        }

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
            self::FIRST_COURSES_CHICKEN_LABEL => self::FIRST_COURSES_CHICKEN->value,
            self::FIRST_COURSES_PORK_LABEL => self::FIRST_COURSES_PORK->value,
            self::FIRST_COURSES_BEEF_LABEL => self::FIRST_COURSES_BEEF->value,
            self::FIRST_COURSES_FISH_LABEL => self::FIRST_COURSES_FISH->value,
            self::FIRST_COURSES_VEGETABLES_LABEL => self::FIRST_COURSES_VEGETABLES->value,
            self::MAIN_COURSES_CHICKEN_LABEL => self::MAIN_COURSES_CHICKEN->value,
            self::MAIN_COURSES_PORK_LABEL => self::MAIN_COURSES_PORK->value,
            self::MAIN_COURSES_BEEF_LABEL => self::MAIN_COURSES_BEEF->value,
            self::MAIN_COURSES_FISH_LABEL => self::MAIN_COURSES_FISH->value,
            self::MAIN_COURSES_OFFAL_LABEL => self::MAIN_COURSES_OFFAL->value,
            self::SALADS_CHICKEN_LABEL => self::SALADS_CHICKEN->value,
            self::SALADS_PORK_LABEL => self::SALADS_PORK->value,
            self::SALADS_BEEF_LABEL => self::SALADS_BEEF->value,
            self::SALADS_FISH_LABEL => self::SALADS_FISH->value,
            self::PREPARATIONS_BERRIES_FRUITS_LABEL => self::PREPARATIONS_BERRIES_FRUITS->value,
            self::PREPARATIONS_VEGETABLES_LABEL => self::PREPARATIONS_VEGETABLES->value,
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
            self::FIRST_COURSES_CHICKEN->value => self::FIRST_COURSES_CHICKEN_LABEL,
            self::FIRST_COURSES_PORK->value => self::FIRST_COURSES_PORK_LABEL,
            self::FIRST_COURSES_BEEF->value => self::FIRST_COURSES_BEEF_LABEL,
            self::FIRST_COURSES_FISH->value => self::FIRST_COURSES_FISH_LABEL,
            self::FIRST_COURSES_VEGETABLES->value => self::FIRST_COURSES_VEGETABLES_LABEL,
            self::MAIN_COURSES_CHICKEN->value => self::MAIN_COURSES_CHICKEN_LABEL,
            self::MAIN_COURSES_PORK->value => self::MAIN_COURSES_PORK_LABEL,
            self::MAIN_COURSES_BEEF->value => self::MAIN_COURSES_BEEF_LABEL,
            self::MAIN_COURSES_FISH->value => self::MAIN_COURSES_FISH_LABEL,
            self::MAIN_COURSES_OFFAL->value => self::MAIN_COURSES_OFFAL_LABEL,
            self::SALADS_CHICKEN->value => self::SALADS_CHICKEN_LABEL,
            self::SALADS_PORK->value => self::SALADS_PORK_LABEL,
            self::SALADS_BEEF->value => self::SALADS_BEEF_LABEL,
            self::SALADS_FISH->value => self::SALADS_FISH_LABEL,
            self::PREPARATIONS_BERRIES_FRUITS->value => self::PREPARATIONS_BERRIES_FRUITS_LABEL,
            self::PREPARATIONS_VEGETABLES->value => self::PREPARATIONS_VEGETABLES_LABEL,
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
            self::FIRST_COURSES_CHICKEN => self::FIRST_COURSES_CHICKEN_LABEL,
            self::FIRST_COURSES_PORK => self::FIRST_COURSES_PORK_LABEL,
            self::FIRST_COURSES_BEEF => self::FIRST_COURSES_BEEF_LABEL,
            self::FIRST_COURSES_FISH => self::FIRST_COURSES_FISH_LABEL,
            self::FIRST_COURSES_VEGETABLES => self::FIRST_COURSES_VEGETABLES_LABEL,
            self::MAIN_COURSES_CHICKEN => self::MAIN_COURSES_CHICKEN_LABEL,
            self::MAIN_COURSES_PORK => self::MAIN_COURSES_PORK_LABEL,
            self::MAIN_COURSES_BEEF => self::MAIN_COURSES_BEEF_LABEL,
            self::MAIN_COURSES_FISH => self::MAIN_COURSES_FISH_LABEL,
            self::MAIN_COURSES_OFFAL => self::MAIN_COURSES_OFFAL_LABEL,
            self::SALADS_CHICKEN => self::SALADS_CHICKEN_LABEL,
            self::SALADS_PORK => self::SALADS_PORK_LABEL,
            self::SALADS_BEEF => self::SALADS_BEEF_LABEL,
            self::SALADS_FISH => self::SALADS_FISH_LABEL,
            self::PREPARATIONS_BERRIES_FRUITS => self::PREPARATIONS_BERRIES_FRUITS_LABEL,
            self::PREPARATIONS_VEGETABLES => self::PREPARATIONS_VEGETABLES_LABEL,
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
            self::FIRST_COURSES_CHICKEN->value => self::FIRST_COURSES_CHICKEN_LABEL,
            self::FIRST_COURSES_PORK->value => self::FIRST_COURSES_PORK_LABEL,
            self::FIRST_COURSES_BEEF->value => self::FIRST_COURSES_BEEF_LABEL,
            self::FIRST_COURSES_FISH->value => self::FIRST_COURSES_FISH_LABEL,
            self::FIRST_COURSES_VEGETABLES->value => self::FIRST_COURSES_VEGETABLES_LABEL,
            self::MAIN_COURSES_CHICKEN->value => self::MAIN_COURSES_CHICKEN_LABEL,
            self::MAIN_COURSES_PORK->value => self::MAIN_COURSES_PORK_LABEL,
            self::MAIN_COURSES_BEEF->value => self::MAIN_COURSES_BEEF_LABEL,
            self::MAIN_COURSES_FISH->value => self::MAIN_COURSES_FISH_LABEL,
            self::MAIN_COURSES_OFFAL->value => self::MAIN_COURSES_OFFAL_LABEL,
            self::SALADS_CHICKEN->value => self::SALADS_CHICKEN_LABEL,
            self::SALADS_PORK->value => self::SALADS_PORK_LABEL,
            self::SALADS_BEEF->value => self::SALADS_BEEF_LABEL,
            self::SALADS_FISH->value => self::SALADS_FISH_LABEL,
            self::PREPARATIONS_BERRIES_FRUITS->value => self::PREPARATIONS_BERRIES_FRUITS_LABEL,
            self::PREPARATIONS_VEGETABLES->value => self::PREPARATIONS_VEGETABLES_LABEL,
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
            self::FIRST_COURSES_CHICKEN_LABEL => self::FIRST_COURSES_CHICKEN,
            self::FIRST_COURSES_PORK_LABEL => self::FIRST_COURSES_PORK,
            self::FIRST_COURSES_BEEF_LABEL => self::FIRST_COURSES_BEEF,
            self::FIRST_COURSES_FISH_LABEL => self::FIRST_COURSES_FISH,
            self::FIRST_COURSES_VEGETABLES_LABEL => self::FIRST_COURSES_VEGETABLES,
            self::MAIN_COURSES_CHICKEN_LABEL => self::MAIN_COURSES_CHICKEN,
            self::MAIN_COURSES_PORK_LABEL => self::MAIN_COURSES_PORK,
            self::MAIN_COURSES_BEEF_LABEL => self::MAIN_COURSES_BEEF,
            self::MAIN_COURSES_FISH_LABEL => self::MAIN_COURSES_FISH,
            self::MAIN_COURSES_OFFAL_LABEL => self::MAIN_COURSES_OFFAL,
            self::SALADS_CHICKEN_LABEL => self::SALADS_CHICKEN,
            self::SALADS_PORK_LABEL => self::SALADS_PORK,
            self::SALADS_BEEF_LABEL => self::SALADS_BEEF,
            self::SALADS_FISH_LABEL => self::SALADS_FISH,
            self::PREPARATIONS_BERRIES_FRUITS_LABEL => self::PREPARATIONS_BERRIES_FRUITS,
            self::PREPARATIONS_VEGETABLES_LABEL => self::PREPARATIONS_VEGETABLES,
        };
    }
}
