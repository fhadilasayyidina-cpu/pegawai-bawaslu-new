import http from 'k6/http';
import { check, group } from 'k6';

export const options = {
    vus: 10,
    duration: '10s',
};

export default function () {

    group('Speed Test', () => {
        const res = http.get(
            'http://192.168.1.7:82/speed-test',
            {
                tags: {
                    endpoint: 'speed-test',
                },
            }
        );

        check(res, {
            'speed-test 200': (r) => r.status === 200,
        });
    });


    group('Login', () => {
        const res = http.get(
            'http://192.168.1.7:82/login',
            {
                tags: {
                    endpoint: 'login',
                },
            }
        );

        check(res, {
            'login 200': (r) => r.status === 200,
        });
    });


    group('Dashboard', () => {
        const res = http.get(
            'http://192.168.1.7:82/admin/dashboard',
            {
                tags: {
                    endpoint: 'dashboard',
                },
            }
        );

        check(res, {
            'dashboard 200': (r) => r.status === 200,
        });
    });

}