const noop = () => Promise.resolve({ data: { data: { lists: [] } } });

export const BASE_URL = "http://localhost";

export default {
    get: noop,
    post: noop,
    defaults: { headers: { common: {} } },
};
