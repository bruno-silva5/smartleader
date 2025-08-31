export const getTenantInfo = () => {
    const hostname = window.location.hostname;
    const parts = hostname.split('.');

    if (parts.length >= 2) {

        return {
            tenant: parts[0],
            baseUrl: `http://${hostname}:8080`
        };
    }

    return {
        tenant: null,
        baseUrl: 'http://localhost:8080'
    };
};

// Create axios base URL with tenant
export const getApiBaseUrl = () => {
    const { baseUrl } = getTenantInfo();
    return `${baseUrl}/api`;
};
