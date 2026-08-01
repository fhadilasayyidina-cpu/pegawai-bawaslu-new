import http from 'k6/http';
import { check, group } from 'k6';

export function runSpeedTest(baseUrl) {
    group('Speed Test', () => {
        const res = http.get(`${baseUrl}/speed-test`, {
            tags: { endpoint: 'speed-test' },
        });

        check(res, {
            'speed-test status 200': (r) => r.status === 200,
        });
    });
}
