import http from 'k6/http';
import { check, group } from 'k6';

export function runLogin(baseUrl) {
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
}
