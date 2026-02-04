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