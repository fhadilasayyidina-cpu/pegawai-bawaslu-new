import { Notyf } from 'notyf';
import 'notyf/notyf.min.css'; // Jangan lupa CSS-nya

// Buat instance Notyf
const notyf = new Notyf({
    duration: 3000,
    position: { x: 'right', y: 'top' },
});

// Dengar event dari Livewire
document.addEventListener('livewire:init', () => {
    Livewire.on('toast', (event) => {
        const data = Array.isArray(event) ? event[0] : event;

        if (data && data.message) {
            const type = data.type || 'success';
            if (type === 'success') {
                notyf.success(data.message);
            } else if (type === 'error') {
                notyf.error(data.message);
            }
        }
    });
});

const animateStatisticValues = () => {
    document.querySelectorAll('.statistic-value[data-stat-value]').forEach((element) => {
        if (element.dataset.animated === 'true') return;

        const value = Number(element.dataset.statValue);
        if (!Number.isFinite(value)) return;

        element.dataset.animated = 'true';
        const duration = 650;
        const startedAt = performance.now();

        const updateValue = (now) => {
            const progress = Math.min((now - startedAt) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            element.textContent = Math.round(value * eased).toLocaleString('id-ID');

            if (progress < 1) requestAnimationFrame(updateValue);
        };

        requestAnimationFrame(updateValue);
    });
};

document.addEventListener('DOMContentLoaded', animateStatisticValues);
document.addEventListener('livewire:navigated', animateStatisticValues);
document.addEventListener('livewire:init', () => {
    Livewire.hook('morph.updated', () => requestAnimationFrame(animateStatisticValues));
});

const refreshDashboardChartStyles = () => {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#cbd5e1' : '#475569';

    document.querySelectorAll('.apexcharts-text, .apexcharts-datalabel, .apexcharts-datalabel-label, .apexcharts-datalabel-value').forEach((element) => {
        element.style.fill = textColor;
        element.style.textShadow = 'none';
        element.style.filter = 'none';
    });

    document.querySelectorAll('.apexcharts-tooltip-text').forEach((element) => {
        element.style.color = '#1e293b';
    });
};

const scheduleDashboardChartRefresh = () => {
    requestAnimationFrame(refreshDashboardChartStyles);
    window.setTimeout(refreshDashboardChartStyles, 150);
};

window.downloadChart = (elementId) => {
    const container = document.getElementById(elementId);
    const chartElement = container?.querySelector('[x-data]');
    const chart = chartElement && window.Alpine ? window.Alpine.$data(chartElement)?.chart : null;

    if (!chart) {
        console.error('Chart instance not available:', elementId);
        return;
    }

    chart.dataURI()
        .then(({ imgURI }) => {
            const link = document.createElement('a');
            link.href = imgURI;
            link.download = `${elementId}-${Date.now()}.png`;
            link.click();
        })
        .catch((error) => console.error('Failed to export chart:', error));
};

document.addEventListener('DOMContentLoaded', () => {
    scheduleDashboardChartRefresh();

    new MutationObserver(scheduleDashboardChartRefresh).observe(document.body, {
        childList: true,
        subtree: true,
    });
});

document.addEventListener('livewire:navigated', scheduleDashboardChartRefresh);
