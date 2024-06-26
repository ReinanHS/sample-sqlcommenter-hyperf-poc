import http from 'k6/http';
import { check } from 'k6';

export const options = {
    stages: [
        { target: 100, duration: '10s' },
    ],
};

export default function () {
    const result = http.get('https://sample-sqlcommenter-hyperf-poc-5dgh2ctppa-uc.a.run.app');
    check(result, {
        'http response status code is 200': result.status === 200,
    });
}