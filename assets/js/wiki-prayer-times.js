function renderPrayerTimesWidget(
  city = wptSettings.defaultCity,
  country = wptSettings.defaultCountry,
  method = wptSettings.calculationMethod
) {
  const cacheKey = `wptPrayerTimes_${city}_${country}`;
  const cacheDuration = wptSettings.cacheDuration * 1000; // Convert seconds to milliseconds
  const now = new Date().getTime();

  // Check cache
  const cachedData = localStorage.getItem(cacheKey);
  if (cachedData) {
    const { timestamp, data } = JSON.parse(cachedData);
    if (now - timestamp < cacheDuration) {
      console.log("Using cached prayer times.");
      return updatePrayerTimes(data);
    }
  }

  // Fetch new data if cache is expired or not found
  console.log("Fetching new prayer times...");
  const apiUrl = `https://api.aladhan.com/v1/timingsByCity?city=${encodeURIComponent(
    city
  )}&country=${encodeURIComponent(country)}&method=${method}`;

  fetch(apiUrl)
    .then((response) => response.json())
    .then(({ code, data }) => {
      if (code !== 200) throw new Error("Invalid API response");

      // Store response in cache
      localStorage.setItem(cacheKey, JSON.stringify({ timestamp: now, data }));

      updatePrayerTimes(data);
    })
    .catch((error) => {
      console.error("Prayer Times Error:", error);
      document.getElementById("wptPrayerTimes").innerHTML =
        "<p>Error loading prayer times. Please try again later.</p>";
    });
}

function formatTime(time, format = wptSettings.timeFormat) {
  const [hours, minutes] = time.split(":").map(Number);
  if (format === "12h") {
    const period = hours >= 12 ? "PM" : "AM";
    const hours12 = hours % 12 || 12;
    return `${hours12}:${minutes.toString().padStart(2, "0")} ${period}`;
  }
  return time;
}

function updatePrayerTimes(data) {
  const { timings, date } = data;
  const now = new Date();

  const prayerTimes = [
    { name: "Fajr", time: timings.Fajr },
    { name: "Sunrise", time: timings.Sunrise },
    { name: "Dhuhr", time: timings.Dhuhr },
    { name: "Asr", time: timings.Asr },
    { name: "Maghrib", time: timings.Maghrib },
    { name: "Isha", time: timings.Isha },
  ].map((prayer) => {
    const [hours, minutes] = prayer.time.split(":").map(Number);
    const prayerDateTime = new Date(now);
    prayerDateTime.setHours(hours, minutes, 0, 0);
    return { ...prayer, dateTime: prayerDateTime };
  });

  let nextPrayer = prayerTimes.find((prayer) => prayer.dateTime > now) || {
    ...prayerTimes[0],
    dateTime: new Date(now.setDate(now.getDate() + 1)),
  };

  const prayerTimesContainer = document.getElementById("wptPrayerTimes");
  let html = "";

  // Add Hijri date if enabled
  if (wptSettings.showHijriDate) {
    html += `<p id="wptIslamicDate">${date.hijri.day} ${date.hijri.month.en} ${date.hijri.year} AH</p>`;
  }

  // Add prayer times
  html += prayerTimes
    .map(
      (prayer) => `
        <div class="prayer-item ${
          wptSettings.highlightNextPrayer && nextPrayer.name === prayer.name
            ? "next-prayer"
            : ""
        }">
            <span>${prayer.name}</span>
            <span class="prayer-time">${formatTime(prayer.time)}</span>
        </div>
    `
    )
    .join("");

  // Add countdown if enabled
  if (wptSettings.showCountdown) {
    html += '<p id="wptCountdown"></p>';
  }

  prayerTimesContainer.innerHTML = html;

  // Update countdown if enabled
  if (wptSettings.showCountdown) {
    function updateCountdown() {
      const now = new Date();
      const diff = nextPrayer.dateTime - now;
      if (diff > 0) {
        const hours = Math.floor(diff / 3600000);
        const minutes = Math.floor((diff % 3600000) / 60000);
        const seconds = Math.floor((diff % 60000) / 1000);
        document.getElementById(
          "wptCountdown"
        ).textContent = `Next: ${nextPrayer.name} in ${hours}h ${minutes}m ${seconds}s`;
      } else {
        clearInterval(countdownInterval);
        renderPrayerTimesWidget(city, country, method);
      }
    }
    updateCountdown();
    const countdownInterval = setInterval(updateCountdown, 1000);
  }
}

// Initialize on page load
jQuery(document).ready(function ($) {
  // Initialize all prayer time widgets on the page
  $(".wiki-prayer-times-widget").each(function () {
    const $widget = $(this);
    const city = $widget.data("city") || wptSettings.defaultCity;
    const country = $widget.data("country") || wptSettings.defaultCountry;
    const method = $widget.data("method") || wptSettings.calculationMethod;
    const showHijri = $widget.data("show-hijri") !== false;
    const showCountdown = $widget.data("show-countdown") !== false;
    const highlightNext = $widget.data("highlight-next") !== false;

    // Override global settings with widget-specific settings
    const widgetSettings = {
      ...wptSettings,
      showHijriDate: showHijri,
      showCountdown: showCountdown,
      highlightNextPrayer: highlightNext,
    };

    // Store original settings
    const originalSettings = { ...wptSettings };

    // Apply widget-specific settings
    Object.assign(wptSettings, widgetSettings);

    // Render widget
    renderPrayerTimesWidget(city, country, method);

    // Restore original settings
    Object.assign(wptSettings, originalSettings);
  });
});
