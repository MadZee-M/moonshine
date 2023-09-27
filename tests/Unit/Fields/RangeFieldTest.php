<?php

declare(strict_types=1);

use MoonShine\Contracts\Fields\DefaultValueTypes\DefaultCanBeArray;
use MoonShine\Contracts\Fields\DefaultValueTypes\DefaultCanBeNumeric;
use MoonShine\Fields\RangeField;

uses()->group('fields');

beforeEach(function (): void {
    $this->field = RangeField::make('Range')->fromTo('start', 'end');
});

describe('basic methods', function () {
    it('change preview', function () {
        expect($this->field->changePreview(static fn () => 'changed'))
            ->preview()
            ->toBe('changed');
    });

    it('formatted value', function () {
        $field = RangeField::make('Range', formatted: static fn () => ['changed'])
            ->fromTo('start', 'end')
            ->fill([]);

        expect($field->toFormattedValue())
            ->toBe(['changed']);
    });

    it('default value', function () {
        $field = RangeField::make('Range')
            ->fromTo('start', 'end')
            ->default([0, 100]);

        expect($field->toValue())
            ->toBe([0, 100]);

        $field = RangeField::make('Range')
            ->fromTo('start', 'end')
            ->default([0, 100])
            ->fill(['start' => 10, 'end' => 90])
        ;

        expect($field->toValue())
            ->toBe(['start' => 10, 'end' => 90]);
    });

    it('applies', function () {
        $field = RangeField::make('Range')
            ->fromTo('start', 'end')
        ;

        expect($field->onApply(fn ($data) => ['onApply'])->apply(fn ($data) => $data, []))
            ->toBe(['onApply'])
            ->and($field->onBeforeApply(fn ($data) => ['onBeforeApply'])->beforeApply([]))
            ->toBe(['onBeforeApply'])
            ->and($field->onAfterApply(fn ($data) => ['onAfterApply'])->afterApply([]))
            ->toBe(['onAfterApply'])
            ->and($field->onAfterDestroy(fn ($data) => ['onAfterDestroy'])->afterDestroy([]))
            ->toBe(['onAfterDestroy'])
        ;
    });
});

describe('common field methods', function () {
    it('names', function (): void {
        expect($this->field)
            ->name()
            ->toBe('range[]')
            ->name('start')
            ->toBe('range[start]');
    });

    it('correct interfaces', function (): void {
        expect($this->field)
            ->toBeInstanceOf(DefaultCanBeNumeric::class)
            ->toBeInstanceOf(DefaultCanBeArray::class)
        ;
    });

    it('type', function (): void {
        expect($this->field->type())
            ->toBe('number');
    });

    it('view', function (): void {
        expect($this->field->getView())
            ->toBe('moonshine::fields.range');
    });

    it('preview', function (): void {
        expect($this->field->fill(['start' => 0, 'end' => 100])->preview())
            ->toBe('0 - 100');
    });

    it('is group', function (): void {
        expect($this->field->isGroup())
            ->toBeTrue();
    });
});
