import http from 'k6/http';
import { check } from 'k6';

export const options = {
    stages: [
        { duration: '1m', target: 50 },
        { duration: '1m', target: 100 },
        { duration: '1m', target: 200 },
        { duration: '1m', target: 300 },
        { duration: '1m', target: 400 },
        { duration: '1m', target: 500 },
    ],
    thresholds: {
        http_req_duration: ['p(95)<3000'], // 95% das requisições devem ser concluídas em menos de 2 segundos
    },
};

export default function () {
    const result = http.get(`${__ENV.APP_HOSTNAME}/departments`);

    check(result, {
        'http response status code is 200': (r) => r.status === 200,
    });
}
