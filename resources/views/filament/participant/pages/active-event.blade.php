<x-filament-panels::page>
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
