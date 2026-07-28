import http from 'k6/http';
import { sleep, check } from 'k6';


export const options = {
    vus: 3,       // Simulasikan 20 pengguna palsu
    duration: '2s', // Uji selama 30 detik
};

export default function () {
    // Sesuaikan URL ini dengan alamat local server Laragon Anda
    let url = 'http://127.0.0.1:8000/admin/dashboard';
    let res = http.get(url);
    http.get(url);


    // PERINTAH CEK: Pastikan halaman mengandung kata "Pegawai" atau "Dashboard"
    check(res, {
        'apakah benar halaman dashboard': (r) => r.body.includes('Dashboard'),
    });


    sleep(1);
}
