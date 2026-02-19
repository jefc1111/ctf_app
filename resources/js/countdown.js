document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-countdown]').forEach(el => {
        const startTime = new Date(el.dataset.startTime);
        const endTime = new Date(el.dataset.endTime);
        const labelEl = document.getElementById(el.dataset.labelId);

        const getStatus = () => {
            const now = new Date();
            if (now < startTime) return 'pending';
            if (now < endTime) return 'in-progress';
            return 'complete';
        };

        const updateLabel = (status) => {
            if (!labelEl) return;
            if (status === 'pending') labelEl.textContent = 'Starts in';
            else if (status === 'in-progress') labelEl.textContent = 'Ends in';
            else labelEl.textContent = 'Event has finished';
        };

        const init = () => {
            const status = getStatus();
            updateLabel(status);

            if (status === 'complete') {
                el.textContent = '';
                return;
            }

            const target = status === 'pending' ? startTime : endTime;

            const inline = el.dataset.inline;

            simplyCountdown(el, {
                year: target.getFullYear(),
                month: target.getMonth() + 1,
                day: target.getDate(),
                hours: target.getHours(),
                minutes: target.getMinutes(),
                seconds: target.getSeconds(),
                countUp: false,
                zeroPad: inline,
                plural: !inline,
                inline: inline,
                inlineSeparator: ' ',
                inlineClass: 'simply-countdown-inline',
                words: { // Custom labels, with lambda for plurals
                    days: { root: inline ? 'd' : 'day', lambda: (root, n) => (n > 1 && ! inline ? root + 's' : root) },
                    hours: { root: inline ? 'h' : 'hour', lambda: (root, n) => (n > 1  && ! inline ? root + 's' : root) },
                    minutes: { root: inline ? 'm' : 'minute', lambda: (root, n) => (n > 1  && ! inline ? root + 's' : root) },
                    seconds: { root: inline ? 's' : 'second', lambda: (root, n) => (n > 1  && ! inline ? root + 's' : root) }
                },
                onEnd: init, // handles pending -> in-progress transition too
            });
        };

        init();
    });
});