import { useState, useEffect } from "react";
import NodeWrapper from "./NodeWrapper.jsx";
import { Position, Handle } from "reactflow";

export default function SendButtonNode({ id, data, setNodes }) {
    const handles = [{ type: "target", position: Position.Left }];
    const [body, setBody] = useState(data.body || "");
    const [buttons, setButtons] = useState(
        data.buttons && data.buttons.length > 0
            ? data.buttons
            : [
                  { text: "Button_1", target_node_id: null },
                  { text: "Button_2", target_node_id: null },
              ]
    );
    const [footer, setFooter] = useState(data.footer || "");

    useEffect(() => {
        setNodes((nds) =>
            nds.map((node) =>
                node.id === id
                    ? { ...node, data: { ...node.data, body, buttons, footer } }
                    : node
            )
        );
    }, [body, buttons, footer, id, setNodes]);

    const addButton = () => {
        if (buttons.length >= 3) return;
        setButtons([
            ...buttons,
            { text: `Button_${buttons.length + 1}`, target_node_id: null },
        ]);
    };

    const removeButton = (index) => {
        setButtons(buttons.filter((_, i) => i !== index));
    };

    const handleButtonChange = (index, value) => {
        if (value.length > 20) {
            notify("error", "The button label cannot exceed 20 characters.");
            return;
        }
        const updated = [...buttons];
        updated[index].text = value;
        setButtons(updated);
    };

    const handleBodyChange = (value) => {
        if (body.length > 1024) {
            notify("error", "The button body cannot exceed 1024 characters.");
            return;
        }
        setBody(value);
    };

    const handleFooterChange = (value) => {
        if (footer.length > 60) {
            notify("error", "The footer text cannot exceed 60 characters.");
            return;
        }
        setFooter(value);
    };

    return (
        <NodeWrapper
            id={id}
            setNodes={setNodes}
            title={
                <h6 className="mb-0">
                    <i className="las las la-stream"></i> Button Message
                </h6>
            }
            content={
                <div style={{ minWidth: "280px" }}>
                    <textarea
                        className="form-control form--control mb-2"
                        rows={2}
                        placeholder="Enter message body..."
                        value={body}
                        maxLength={1024}
                        onChange={(e) => handleBodyChange(e.target.value)}
                    />

                    <input
                        type="text"
                        className="form-control form--control mb-3"
                        placeholder="Enter footer text..."
                        value={footer}
                        maxLength={60}
                        onChange={(e) => handleFooterChange(e.target.value)}
                    />

                    <div className="text-sm text-gray-700">
                        <label className="d-block mb-1">Buttons (max 3)</label>

                        {buttons.map((btn, index) => (
                            <div
                                key={index}
                                className="d-flex gap-2 mb-2"
                                style={{ position: "relative" }}
                            >
                                <input
                                    type="text"
                                    className="form-control form--control"
                                    placeholder={`Button ${index + 1}`}
                                    value={btn.text}
                                    maxLength={20}
                                    onChange={(e) =>
                                        handleButtonChange(
                                            index,
                                            e.target.value
                                        )
                                    }
                                />
                                <button
                                    className="btn btn--danger btn--sm"
                                    onClick={() => removeButton(index)}
                                >
                                    <i className="las la-trash"></i>
                                </button>

                                <Handle
                                    type="source"
                                    id={`button-${index}`}
                                    position={Position.Right}
                                    style={{
                                        top: "50%",
                                        transform: "translateY(-50%)",
                                        right: "-22px",
                                        width: "10px",
                                        height: "10px",
                                    }}
                                />
                            </div>
                        ))}

                        {buttons.length < 3 && (
                            <button
                                className="btn btn--primary btn--sm w-100"
                                onClick={addButton}
                            >
                                <i className="las la-plus me-1"></i>
                                Add Button
                            </button>
                        )}
                    </div>
                </div>
            }
            handles={handles}
        />
    );
}
