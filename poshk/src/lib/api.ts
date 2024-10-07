import axios from 'axios';

// Ubah ke localhost dengan port yang sesuai
let urlMain = 'http://localhost/';  // Menggunakan localhost

let url = urlMain + 'api/apps/hk/';

export let axi = axios.create({
    baseURL: url
});

axi.defaults.headers.post['Content-Type'] = 'application/json';
axi.defaults.headers.post['Accept'] = 'application/json';

export function apiCall(uri: string, data: any) {
	if (uri) {
		return axi.post(uri, data);
	}
}
