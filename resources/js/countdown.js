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
console.log(el.dataset)
            simplyCountdown(el, {
                year: target.getFullYear(),
                month: target.getMonth() + 1,
                day: target.getDate(),
                hours: target.getHours(),
                minutes: target.getMinutes(),
                seconds: target.getSeconds(),
                countUp: false,
                zeroPad: el.dataset.inline,
                plural: ! el.dataset.inline,
                inline: el.dataset.inline,
                inlineSeparator: ' ',
                inlineClass: 'simply-countdown-inline',
                onEnd: init, // handles pending -> in-progress transition too
            });
        };

        init();
    });
});