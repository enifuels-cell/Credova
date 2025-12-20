import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
<<<<<<< HEAD
=======

// Configure base URL for API calls to ensure HTTPS
const baseURL = window.location.protocol + '//' + window.location.host;
window.axios.defaults.baseURL = baseURL;

// Add CSRF token to all requests
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Global axios response interceptor for error handling
window.axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 419) {
            // CSRF token mismatch - reload page
            window.location.reload();
        }
        return Promise.reject(error);
    }
);
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
