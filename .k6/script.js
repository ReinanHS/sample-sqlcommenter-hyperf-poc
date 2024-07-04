import http from 'k6/http';
import { check } from 'k6';

export const options = {
    stages: [
        { duration: '1m', target: 100 }, // Rampa até 100 usuários em 2 minutos
        { duration: '4m', target: 100 }, // Mantém 100 usuários por 5 minutos
        { duration: '2m', target: 200 }, // Aumenta para 200 usuários em 2 minutos
        { duration: '4m', target: 200 }, // Mantém 200 usuários por 5 minutos
        { duration: '2m', target: 0 },   // Reduz para 0 usuários em 2 minutos
    ],
    thresholds: {
        http_req_duration: ['p(95)<3000'], // 95% das requisições devem ser concluídas em menos de 2 segundos
    },
};

export default function () {
    const result = http.get('https://sample-sqlcommenter-hyperf-poc-5dgh2ctppa-uc.a.run.app/employees');
    check(result, {
        'http response status code is 200': (r) => r.status === 200,
    });
}
