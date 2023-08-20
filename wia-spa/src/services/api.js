import axios from 'axios';

const apiClient = axios.create({
    baseURL: import.meta.env.VITE_APP_API_URL,
    headers: {
        'Content-Type': 'application/json'
    },
});

export const describeImages = async (searchTerm) => {
    try {
        const response = await apiClient.post('/webpages/images/describe', { url: searchTerm });
        return response.data.data;
    } catch (error) {
        throw error;
    }
};