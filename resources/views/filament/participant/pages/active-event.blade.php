<x-filament-panels::page>    
    @assets
        <link rel="stylesheet" href="{{ asset('css/simplyCountdown-cyber.min.css') }}">
        <script src="{{ asset('js/simplyCountdown.umd.js') }}"></script>
    @endassets

    @if ($this->event)
        @if ($this->event->isInProgress())
            {{ $this->eventInfolist }}
        @else
            <p>Case details will show here.</p>
            <p>Please refresh the page when the event has started.</p>
        @endif
    @else
        You must claim a valid Ticket Purchase and be part of a Team to be able to participate.
    @endif
</x-filament-panels::page>
