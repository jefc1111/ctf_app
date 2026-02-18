@props(['event', 'variant' => 'default'])

@php $id = 'countdown-' . $event->id; @endphp

<div @class([
    'flex items-center gap-3' => $variant === 'compact',
])>
    <span
        wire:ignore 
        id="{{ $id }}-label"
        @class([
            'simply-countdown-cyber' => $variant === 'default',
            'text-sm text-gray-300 whitespace-nowrap' => $variant === 'compact',
        ])
    ></span>
    <div
        wire:ignore
        data-countdown
        data-start-time="{{ $event->start_time->toIso8601String() }}"
        data-end-time="{{ $event->end_time->toIso8601String() }}"
        data-label-id="{{ $id }}-label"
        @class([
            'simply-countdown-cyber' => $variant === 'default',
            'simply-countdown-cyber simply-countdown-cyber--compact' => $variant === 'compact',
        ])
    ></div>
</div>