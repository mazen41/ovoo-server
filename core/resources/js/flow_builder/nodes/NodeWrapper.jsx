import { Handle } from "reactflow";
import { v4 as uuidv4 } from "uuid";
export default function NodeWrapper({ id, title, content, handles, setNodes }) {
    const handleDelete = () =>
        setNodes((nds) => nds.filter((n) => n.id !== id));

    const handleDuplicate = () => {
        setNodes((nds) => {
            const nodeToCopy = nds.find((n) => n.id === id);
            if (!nodeToCopy) return nds;

            const newNodeReactId = uuidv4();
            const newBackendNodeId = uuidv4();

            const clonedData = JSON.parse(
                JSON.stringify(nodeToCopy.data || {})
            );

            clonedData.nodeId = newBackendNodeId;
            if ("image" in clonedData) clonedData.image = null;
            if ("fileName" in clonedData) clonedData.fileName = null;
            if ("video" in clonedData) clonedData.video = null;
            if ("audio" in clonedData) clonedData.audio = null;

            const newNode = {
                id: newNodeReactId,
                type: nodeToCopy.type,
                position: {
                    x: (nodeToCopy.position?.x || 0) + 150,
                    y: (nodeToCopy.position?.y || 0) + 150,
                },
                data: clonedData,
            };

            return [...nds, newNode];
        });
    };

    return (
        <div className="relative shadow-md bg-white text-dark rounded-md node-card">
            <div className="d-flex align-items-center justify-content-between gap-3">
                {title || <strong>Node</strong>}
                <div>
                    <button
                        onClick={handleDuplicate}
                        className="me-1"
                        title="Duplicate Node"
                    >
                        <i className="las la-copy text--info"></i>
                    </button>
                    <button onClick={handleDelete} title="Delete Node">
                        <i className="las la-trash text--danger"></i>
                    </button>
                </div>
            </div>
            <div className="mt-2">{content}</div>

            {handles?.map((h, index) => (
                <Handle
                    key={index}
                    type={h.type}
                    position={h.position}
                    style={{ width: "8px", height: "8px" }}
                />
            ))}
        </div>
    );
}
