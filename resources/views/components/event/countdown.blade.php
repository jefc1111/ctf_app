@props(['event', 'variant' => 'default'])
@php $id = 'countdown-' . $event->id; @endphp

<div @class([
    'simply-countdown-inline' => $variant === 'compact'
])>
    <span
        wire:ignore 
        id="{{ $id }}-label"
    ></span>
    <div
        wire:ignore
        data-countdown
        data-start-time="{{ $event->start_time->toIso8601String() }}"
        data-end-time="{{ $event->end_time->toIso8601String() }}"
        data-label-id="{{ $id }}-label"
        data-inline="{{ $variant === 'compact' }}"
        @class([
            'simply-countdown-cyber' => $variant === 'default',
            'simply-countdown-inline' => $variant === 'compact'
        ])
    ></div>
</div>