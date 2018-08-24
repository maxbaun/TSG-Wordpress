import axios from 'axios';
import {apiUrl} from '../constants';

export default function ({method, route, data}) {
	let request = {
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json'
		},
		method,
		baseURL: apiUrl,
		url: route
	};

	if (data && method === 'GET') {
		request.params = data;
	}

	return axios(request).then(res => res.data);
}
