
<x-filament-panels::page>
    <h1 id="event-countdown-label" class="simply-countdown-cyber"></h1>
    <div id="event-countdown" class="simply-countdown-cyber"></div>
    @assets
        <link rel="stylesheet" href="{{ asset('css/simplyCountdown-cyber.min.css') }}">
        <script src="{{ asset('js/simplyCountdown.umd.js') }}"></script>
    @endassets

    @if ($this->event)  
        @script
            <script>
                let countdown;

                const startTime = new Date('{{ $this->event->start_time->toIso8601String() }}');
                const endTime = new Date('{{ $this->event->end_time->toIso8601String() }}');

                const getProgressStatus = () => {
                    const now = new Date();

                    if (now < startTime) {
                        return 'pending';
                    } else if (now >= startTime && now < endTime) {
                        return 'in-progress';
                    } else {
                        return 'complete';
                    }
                }

                function updateCountdownLabel(status) {
                    const label = document.getElementById('event-countdown-label');
                    
                    if (status === 'pending') {
                        label.textContent = 'Starts in';
                    } else if (status === 'in-progress') {
                        label.textContent = 'Ends in';                    
                    } else {
                        label.textContent = 'Event has finished';
                        //badge.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800';
                    }
                }

                function getCountdownTargetTime(status) {
                    return status === 'pending' ? startTime : endTime;
                }

                const initCountdown = () => {
                    const progressStatus = getProgressStatus()
                    
                    updateCountdownLabel(progressStatus);

                    const countdownTargetTime = getCountdownTargetTime(progressStatus);

                    countdown = simplyCountdown('#event-countdown', {
                        year: countdownTargetTime.getFullYear(), // Target year (required)
                        month: countdownTargetTime.getMonth() + 1, // Target month [1-12] (required)
                        day: countdownTargetTime.getDate(), // Target day [1-31] (required)
                        hours: countdownTargetTime.getHours(), // Target hour [0-23], default: 0
                        minutes: countdownTargetTime.getMinutes(), // Target minute [0-59], default: 0
                        seconds: countdownTargetTime.getSeconds(), // Target second [0-59], default: 0
                        countUp: false, // Count up after reaching zero
                        onEnd: () => {
                            if (progressStatus === 'complete') {
                                const countdownHolder = document.getElementById('#event-countdown');

                                countdownHolder.textContent = "";                            
                            } else {
                                initCountdown(); // Handles the transition from pending -> in progress
                            }                      
                        }, // Callback when countdown ends
                        onStop: () => {}, // Callback when countdown is stopped
                        onResume: () => {}, // Callback when countdown is resumed
                        onUpdate: (params) => {} // Callback when countdown is updated
                    });
                }  
        
                initCountdown();
            </script>
        @endscript
        @if ($this->event->isInProgress())
            {{ $this->eventInfolist }}
        @else
            <p>Case details will show here.</p> 
            <p>Please refresh the page when the event has started.</p>
        @endif
    @else
        No active event. You must claim a valid Ticket Purchase and be part of a Team to be able to participate.
    @endif
</x-filament-panels::page>
