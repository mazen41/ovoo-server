import { useState } from "react";
import NodeWrapper from "./NodeWrapper.jsx";
import { Position } from "reactflow";
import { uploadMedia } from "../uploadMedia.js";

export default function SendDocumentNode({ id, data, setNodes }) {
    const handles = data.handles || [
        { type: "target", position: Position.Left },
        { type: "source", position: Position.Right },
    ];

    const [fileName, setFileName] = useState(
        data.documentName || "No document selected"
    );
    const [isRequesting, setIsRequesting] = useState(false);

    const handleFileChange = async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        setIsRequesting(true);

        try {
            const uploadedUrl = await uploadMedia(id, file, "document");
            setFileName(file.name);
            setNodes((nds) =>
                nds.map((node) =>
                    node.id === id
                        ? {
                              ...node,
                              data: {
                                  ...node.data,
                                  document: uploadedUrl,
                                  documentName: file.name,
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
                    <i className="las la-file-alt"></i> Send Document
                </h6>
            }
            content={
                <div className="document-node">
                    <div>{isRequesting ? "Uploading..." : fileName}</div>

                    <input
                        type="file"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.txt"
                        onChange={handleFileChange}
                        className="form-control form--control w-full"
                    />
                </div>
            }
            handles={handles}
        />
    );
}
