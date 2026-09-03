document.querySelectorAll('.b-counter [data-counter-date]').forEach((counter) => {
  const endTime = Date.parse(counter.dataset.counterDate);
  const daysElement = counter.querySelector('[data-counter-days]');
  const hoursElement = counter.querySelector('[data-counter-hours]');
  const minutesElement = counter.querySelector('[data-counter-minutes]');
  const secondsElement = counter.querySelector('[data-counter-seconds]');

  if (
    Number.isNaN(endTime) ||
    !daysElement ||
    !hoursElement ||
    !minutesElement ||
    !secondsElement
  ) return;

  let intervalId;

  const updateCounter = () => {
    const remainingMilliseconds = Math.max(0, endTime - Date.now());
    const totalSeconds = Math.ceil(remainingMilliseconds / 1000);
    const days = Math.floor(totalSeconds / 86400);
    const hours = Math.floor((totalSeconds % 86400) / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;

    daysElement.textContent = String(days);
    hoursElement.textContent = String(hours).padStart(2, '0');
    minutesElement.textContent = String(minutes).padStart(2, '0');
    secondsElement.textContent = String(seconds).padStart(2, '0');

    if (remainingMilliseconds === 0 && intervalId) {
      window.clearInterval(intervalId);
    }
  };

  updateCounter();

  if (endTime > Date.now()) {
    intervalId = window.setInterval(updateCounter, 1000);
  }
});
