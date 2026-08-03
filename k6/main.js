import { sleep } from 'k6';
import { runSpeedTest } from './speed-test.js';
import { runLogin } from './login-test.js';
import { runDashboard } from './dashboard-test.js';

export const options = {
    vus: 5,
    duration: '5s',
};

const BASE_URL = 'http://192.168.1.7:82';

export default function () {
    // // Jalankan Speed Test
    //runSpeedTest(BASE_URL);

    // // Jalankan Login dan ambil tokennya
    // const token = runLogin(BASE_URL);

    // Jalankan Dashboard menggunakan token dari proses login
    runDashboard(BASE_URL);


}
