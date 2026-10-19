// Scripts Globais da Aplicação
document.addEventListener('DOMContentLoaded', () => {
    // Helper para requisições AJAX com CSRF automático
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    window.apiFetch = async (url, options = {}) => {
        options.headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            ...options.headers
        };

        const response = await fetch(url, options);
        return response.json();
    };

    console.log('App inicializado.');
});
