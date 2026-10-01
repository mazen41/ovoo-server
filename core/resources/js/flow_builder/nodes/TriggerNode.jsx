import { Handle, Position } from "reactflow";
import { useEffect } from "react";
import { v4 as uuidv4 } from "uuid";

export default function TriggerNode({ id, data, setNodes }) {
    useEffect(() => {
        setNodes((nds) =>
            nds.map((node) => {
                if (node.id === id) {
                    return {
                        ...node,
                        data: {
                            ...node.data,
                            nodeId: node.data.nodeId || uuidv4(),
                            trigger: node.data.trigger || "new_message",
                            keyword: node.data.keyword || "",
                            handles: node.data.handles || [
                                { type: "source", position: Position.Right },
                            ],
                        },
                    };
                }
                return node;
            })
        );
    }, [id, setNodes]);

    const handleTriggerChange = (e) => {
        const value = e.target.value;
        setNodes((nds) =>
            nds.map((node) =>
                node.id === id
                    ? {
                          ...node,
                          data: {
                              ...node.data,
                              trigger: value,
                              keyword:
                                  value === "keyword_match"
                                      ? node.data.keyword || ""
                                      : "",
                          },
                      }
                    : node
            )
        );
    };

    const handleKeywordChange = (e) => {
        const keyword = e.target.value;
        setNodes((nds) =>
            nds.map((node) =>
                node.id === id
                    ? { ...node, data: { ...node.data, keyword } }
                    : node
            )
        );
    };

    return (
        <div className="shadow-md bg-white text-dark rounded-md node-card">
            <h6 className="mb-0">
                <i className="las la-bolt text-warning"></i> Trigger
            </h6>
            <div className="mt-2 text-sm">
                <select
                    value={data.trigger}
                    onChange={handleTriggerChange}
                    className="form-select form--control"
                >
                    <option value="new_message">New message</option>
                    <option value="keyword_match">Keyword matches</option>
                </select>
            </div>

            {data.trigger === "keyword_match" && (
                <div className="mt-2">
                    <input
                        type="text"
                        value={data.keyword}
                        onChange={handleKeywordChange}
                        className="form-control form--control"
                        placeholder="Enter keyword..."
                    />
                </div>
            )}

            <Handle
                type="source"
                position={Position.Right}
                style={{ width: "8px", height: "8px" }}
            />
        </div>
    );
}
