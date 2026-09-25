import { useState } from "react";
import NodeWrapper from "./NodeWrapper.jsx";
import { Position } from "reactflow";
import { uploadMedia } from "../uploadMedia.js";

export default function SendImageNode({ id, data, setNodes }) {
    const handles = data.handles || [
        { type: "target", position: Position.Left },
        { type: "source", position: Position.Right },
    ];

    const [preview, setPreview] = useState(data.image || null);
    const [isRequesting, setIsRequesting] = useState(false);

    const handleImageChange = async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        setIsRequesting(true);

        try {
            const uploadedUrl = await uploadMedia(id, file, "image");

            const localPreview = URL.createObjectURL(file);
            setPreview(localPreview);

            setNodes((nds) =>
                nds.map((node) =>
                    node.id === id
                        ? {
                              ...node,
                              data: {
                                  ...node.data,
                                  image: uploadedUrl,
                                  fileName: file.name,
                              },
                          }
                        : node
                )
            );
        } catch (error) {
            notify("error", error.message);
        }
        setIsRequesting(false);
    };

    return (
        <NodeWrapper
            id={id}
            setNodes={setNodes}
            title={
                <h6 className="mb-0">
                    <i className="las la-image"></i> Send Image
                </h6>
            }
            content={
                <div className="image-node">
                    {preview ? (
                        <img src={preview} alt="Preview" />
                    ) : (
                        <span style={{ color: "#999" }}>
                            {isRequesting
                                ? "Uploading..."
                                : "No Image Selected"}
                        </span>
                    )}

                    <input
                        type="file"
                        accept="image/*"
                        onChange={handleImageChange}
                        className="form-control form--control mt-2"
                        style={{
                            opacity: 0,
                            position: "absolute",
                            top: 0,
                            left: 0,
                            width: "100%",
                            height: "100%",
                            cursor: "pointer",
                        }}
                    />
                </div>
            }
            handles={handles}
        />
    );
}
