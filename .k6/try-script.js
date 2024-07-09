import http from 'k6/http';
import { check } from 'k6';
import { randomItem } from 'https://jslib.k6.io/k6-utils/1.2.0/index.js';

export const options = {
    stages: [
        { duration: '1m', target: 50 },
        { duration: '2m', target: 100 },
        { duration: '2m', target: 200 },
        { duration: '4m', target: 50 },
    ],
    thresholds: {
        http_req_duration: ['p(95)<3000'], // 95% das requisições devem ser concluídas em menos de 2 segundos
    },
};

export const routes = ['', 'employees', 'departments', 'titles', 'tchecksum']

export default function () {
    const randomRouter = randomItem(routes);
    const result = http.get(`${__ENV.APP_HOSTNAME}/${randomRouter}`);

    check(result, {
        'http response status code is 200': (r) => r.status === 200,
    });
}
