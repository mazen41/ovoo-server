import axios from "axios";

// Every state changing flow builder request is CSRF protected, so the token rendered into the
// page head has to travel with it. Registering the header as an axios default keeps each call
// site free of the concern.
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");

if (csrfToken) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken;
}

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

export const BASE_URL = document
    .querySelector("meta[name=APP-DOMAIN]")
    .getAttribute("content");

export default axios;
