import { useState } from "react";
import NodeWrapper from "./NodeWrapper.jsx";
import { Position } from "reactflow";
import { uploadMedia } from "../uploadMedia.js";

export default function SendVideoNode({ id, data, setNodes }) {
    const handles = data.handles || [
        { type: "target", position: Position.Left },
        { type: "source", position: Position.Right },
    ];

    const [preview, setPreview] = useState(data.video || null);

    const [isRequesting, setIsRequesting] = useState(false);

    const handleVideoChange = async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        setIsRequesting(true);

        try {
            const uploadedUrl = await uploadMedia(id, file, "video");

            const localPreview = URL.createObjectURL(file);
            setPreview(localPreview);

            setNodes((nds) =>
                nds.map((node) =>
                    node.id === id
                        ? {
                              ...node,
                              data: {
                                  ...node.data,
                                  video: uploadedUrl,
                                  fileName: file.name,
                              },
                          }
                        : node
                )
            );
        } catch (error) {
            notify("error", error.message);
        } finally {
            e.target.value = null;
        }

        setIsRequesting(false);
    };

    return (
        <NodeWrapper
            id={id}
            setNodes={setNodes}
            title={
                <h6 className="mb-0">
                    <i className="las la-video"></i> Send Video
                </h6>
            }
            content={
                <div className="video-node">
                    <div>
                        {preview ? (
                            <video src={preview} controls />
                        ) : (
                            <span style={{ color: "#999" }}>
                                {isRequesting
                                    ? "Uploading..."
                                    : "No video selected"}
                            </span>
                        )}
                    </div>

                    <input
                        type="file"
                        accept="video/*"
                        onChange={handleVideoChange}
                        className="form-control form--control mt-2"
                    />
                </div>
            }
            handles={handles}
        />
    );
}
