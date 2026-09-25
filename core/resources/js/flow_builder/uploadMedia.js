import axios from "axios";

const BASE_URL = document.querySelector("meta[name=APP-DOMAIN]").getAttribute('content');

export async function uploadMedia(nodeId, file, type) {
    const formData = new FormData();
    formData.append("node_id", nodeId);
    formData.append("file", file);
    formData.append("type", type);

    try {
        const response = await axios.post(
            `${BASE_URL}/user/flow-builder/upload-media`,
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        if (response.data.status === "error") {
            throw new Error(response.data.message);
        }
        return response.data.data.mediaPath;
    } catch (error) {
        throw new Error(error.message);
    }
}
