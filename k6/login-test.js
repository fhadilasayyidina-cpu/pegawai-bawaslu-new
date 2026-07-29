import http from 'k6/http';
import { check, group } from 'k6';

export function runLogin(baseUrl) {
    group('Login', () => {
        const res = http.get(
            'http://127.0.0.1:8000/login',
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
