import { useState } from "react";
import NodeWrapper from "./NodeWrapper.jsx";
import { Position } from "reactflow";
import { uploadMedia } from "../uploadMedia.js";

export default function SendAudioNode({ id, data, setNodes }) {
    const handles = data.handles || [
        { type: "target", position: Position.Left },
        { type: "source", position: Position.Right },
    ];

    const [audioFile, setAudioFile] = useState(
        data.audio || { name: "No audio selected", url: null }
    );
    const [isRequesting, setIsRequesting] = useState(false);

    const handleAudioChange = async (e) => {
        const file = e.target.files[0];
        if (!file) return;
        setIsRequesting(true);
        try {
            const uploadedUrl = await uploadMedia(id, file, "audio");

            const localPreview = URL.createObjectURL(file);
            setAudioFile({ name: file.name, url: localPreview });

            setNodes((nds) =>
                nds.map((node) =>
                    node.id === id
                        ? {
                              ...node,
                              data: {
                                  ...node.data,
                                  audio: { name: file.name, url: uploadedUrl },
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
                    <i className="las la-microphone"></i> Send Audio
                </h6>
            }
            content={
                <div className="audio-node">
                    <div>
                        {audioFile.url ? (
                            <audio controls src={audioFile.url} />
                        ) : (
                            <span style={{ color: "#999" }}>
                                {isRequesting
                                    ? "Uploading..."
                                    : "No audio selected"}
                            </span>
                        )}
                    </div>

                    <input
                        type="file"
                        accept="audio/*"
                        onChange={handleAudioChange}
                        className="form-control form--control w-full"
                    />
                </div>
            }
            handles={handles}
        />
    );
}
