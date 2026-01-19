import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

// Initialize Notyf globally
window.notyf = new Notyf({
    duration: 4000,
    position: { x: 'right', y: 'top' },
    dismissible: true
});

// Listen for Livewire dispatch events
document.addEventListener('livewire:init', () => {
    Livewire.on('notyf:show', (data) => {
        window.notyf.open({
            type: data.type ?? 'success',
            message: data.message ?? 'Berhasil'
        });
    });
});
