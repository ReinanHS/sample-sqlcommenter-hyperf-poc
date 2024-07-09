import http from 'k6/http';
import { check } from 'k6';

export const options = {
    stages: [
        { duration: '1m', target: 50 },
        { duration: '2m', target: 100 },
        { duration: '2m', target: 200 },
    ],
    thresholds: {
        http_req_duration: ['p(95)<3000'], // 95% das requisições devem ser concluídas em menos de 2 segundos
    },
};

export default function () {
    const result = http.get(`${__ENV.APP_HOSTNAME}/employees`);

    check(result, {
        'http response status code is 200': (r) => r.status === 200,
    });
}
