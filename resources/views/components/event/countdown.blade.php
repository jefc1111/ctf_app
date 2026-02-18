@props(['event', 'location', 'variant' => 'default'])
@php $id = "countdown-$event->id-$location" @endphp
<div wire:ignore @class([
    'simply-countdown-inline' => $variant === 'compact'
])>
    <span        
        id="{{ $id }}-label"
        @class([
            'simply-countdown-cyber' => $variant === 'default',
        ])
    ></span>
    <div
        data-countdown
        data-start-time="{{ $event->start_time->toIso8601String() }}"
        data-end-time="{{ $event->end_time->toIso8601String() }}"
        data-label-id="{{ $id }}-label"
        data-inline="{{ $variant === 'compact' }}"
        @class([
            'simply-countdown-cyber' => $variant === 'default',
            'simply-countdown-inline' => $variant === 'compact',
        ])
    ></div>
</div>