import http from 'k6/http';
import { check, group } from 'k6';

export function runSpeedTest(baseUrl) {
    group('Speed Test', () => {
        const res = http.get(`${baseUrl}/test`, {
            tags: { endpoint: 'test' },
        });

        check(res, {
            'test status 200': (r) => r.status === 200,
        });
    });
}
