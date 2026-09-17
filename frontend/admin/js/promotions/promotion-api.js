const API_URL =
    'http://localhost:8000/api/admin/promotions';

export async function getPromotions(params = {}) {
    const query =
        new URLSearchParams(params);

    const response =
        await fetch(
            `${API_URL}?${query}`
        );

    return await handleResponse(response);
}

export async function getPromotion(maKM) {
    const response =
        await fetch(
            `${API_URL}/${encodeURIComponent(maKM)}`
        );

    return await handleResponse(response);
}

export async function createPromotion(data) {
    const response =
        await fetch(API_URL, {
            method: 'POST',

            headers: {
                'Content-Type':
                    'application/json'
            },

            body:
                JSON.stringify(data)
        });

    return await handleResponse(response);
}

export async function updatePromotion(
    maKM,
    data
) {
    const response =
        await fetch(
            `${API_URL}/${encodeURIComponent(maKM)}`,
            {
                method: 'PUT',

                headers: {
                    'Content-Type':
                        'application/json'
                },

                body:
                    JSON.stringify(data)
            }
        );

    return await handleResponse(response);
}

export async function deletePromotion(
    maKM
) {
    const response =
        await fetch(
            `${API_URL}/${encodeURIComponent(maKM)}`,
            {
                method: 'DELETE'
            }
        );

    return await handleResponse(response);
}

async function handleResponse(response) {
    const result =
        await response.json();

    if (!response.ok) {
        throw result;
    }

    return result;
}