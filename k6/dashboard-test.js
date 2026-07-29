import http from 'k6/http';
import { check, group } from 'k6';

export function runDashboard(baseUrl, authToken) {
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
