<style>
    .countdown-compact {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-transform: lowercase;        
        font-family: monospace;
    }
    
    .countdown-compact .simply-countdown-inline {
        font-family: monospace;
        letter-spacing: 0.05em;
    }
    
    html:is(.dark) .countdown-compact {
        color: #00FF41;
    }
</style>

@props([
    'event', 
    'location', 
    'variant' => 'default',
    'preamble' => ''
])
@php $id = "countdown-$event->id-$location" @endphp
<div wire:ignore @class([
    'countdown-compact' => $variant === 'compact'
])>
    <span>{{ $preamble }}</span>
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