import ReactDOM from "react-dom/client";
import "reactflow/dist/style.css";
import { useCallback, useMemo, useState } from "react";
import { v4 as uuidv4 } from "uuid";
import ReactFlow, {
    MiniMap,
    Controls,
    Background,
    useNodesState,
    useEdgesState,
    addEdge,
    Position,
} from "reactflow";

import SendTextMessageNode from "./nodes/SendTextMessageNode";
import SendImageNode from "./nodes/SendImageNode";
import SendVideoNode from "./nodes/SendVideoNode";
import SendDocumentNode from "./nodes/SendDocumentNode";
import SendAudioNode from "./nodes/SendAudioNode";
import SendLocationNode from "./nodes/SendLocationNode";
import SendListMessageNode from "./nodes/SendListMessageNode";
import Sidebar from "./nodes/Sidebar";
import TriggerNode from "./nodes/TriggerNode";
import SendCtaUrl from "./nodes/SendCtaUrl";
import SendButtonNode from "./nodes/SendButtonNode";
import axios from "axios";
import SendTemplateNode from "./nodes/SendTemplateNode";

const INNER_HEIGHT = window.innerHeight / 2 - 340;

var triggerType = "new_message";
var ExKeyword = "";

try {
    triggerType = document.getElementById("flow-builder").dataset.trigger;
    ExKeyword = document.getElementById("flow-builder").dataset.keyword;
} catch (e) { }

const initialNodes = [
    {
        id: "1",
        type: "triggerNode",
        position: { x: -700, y: INNER_HEIGHT },
        data: {
            nodeId: "1",
            trigger: triggerType,
            keyword: ExKeyword,
            handles: [{ type: "source", position: Position.Right }],
        },
    },
];

function FlowBuilder() {
    const flowBuilderElement = document.getElementById("flow-builder");
    let existingNodes = [];
    let existingEdges = [];
    let existingName = "";
    let editing = false;
    let existingFlowId = null;

    try {
        existingNodes = JSON.parse(flowBuilderElement.dataset.nodes || "[]");
        existingEdges = JSON.parse(flowBuilderElement.dataset.edges || "[]");
        existingName = flowBuilderElement.dataset.name || "";
        existingFlowId = flowBuilderElement.dataset.id || null;
        if (existingNodes.length) editing = true;
    } catch (e) {
        console.error("Failed to parse existing flow data:", e);
    }

    const [isEditing] = useState(editing);
    const [flowId] = useState(existingFlowId);

    if (existingNodes.length && existingEdges.length) {
        initialNodes.push(...existingNodes);
    }

    const [nodes, setNodes, onNodesChange] = useNodesState(initialNodes);
    const [edges, setEdges, onEdgesChange] = useEdgesState(existingEdges);
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);
    const [showModal, setShowModal] = useState(false);
    const [flowName, setFlowName] = useState(existingName);

    const nodeTypes = useMemo(
        () => ({
            triggerNode: (props) => (
                <TriggerNode {...props} setNodes={setNodes} />
            ),
            textMessage: (props) => (
                <SendTextMessageNode {...props} setNodes={setNodes} />
            ),
            sendImage: (props) => (
                <SendImageNode {...props} setNodes={setNodes} />
            ),
            sendVideo: (props) => (
                <SendVideoNode {...props} setNodes={setNodes} />
            ),
            sendDocument: (props) => (
                <SendDocumentNode {...props} setNodes={setNodes} />
            ),
            sendAudio: (props) => (
                <SendAudioNode {...props} setNodes={setNodes} />
            ),
            sendLocation: (props) => (
                <SendLocationNode {...props} setNodes={setNodes} />
            ),
            sendCtaUrl: (props) => (
                <SendCtaUrl {...props} setNodes={setNodes} />
            ),
            sendList: (props) => (
                <SendListMessageNode {...props} setNodes={setNodes} />
            ),
            sendTemplate: (props) => (
                <SendTemplateNode {...props} setNodes={setNodes} />
            ),
            sendButton: (props) => (
                <SendButtonNode {...props} setNodes={setNodes} />
            ),
        }),
        [setNodes]
    );

    const onDragOver = useCallback((event) => {
        event.preventDefault();
        event.dataTransfer.dropEffect = "move";
    }, []);

    const onDrop = useCallback(
        (event) => {
            event.preventDefault();
            const type = event.dataTransfer.getData("application/reactflow");
            if (!type) return;

            const reactFlowBounds = event.currentTarget.getBoundingClientRect();
            const position = {
                x: event.clientX + window.scrollX - reactFlowBounds.right,
                y: event.clientY + window.scrollY - reactFlowBounds.top,
            };
            const id = uuidv4();
            const newNode = {
                id,
                type,
                position,
                data: {
                    message: "",
                    handles: [
                        { type: "target", position: Position.Left },
                        { type: "source", position: Position.Right },
                    ],
                },
            };
            setNodes((nds) => nds.concat(newNode));
        },
        [setNodes]
    );

    const onConnect = useCallback(
        (params) =>
            setEdges((eds) => addEdge({ ...params, animated: true }, eds)),
        [setEdges]
    );

    const BASE_URL = document
        .querySelector("meta[name=APP-DOMAIN]")
        .getAttribute("content");

    const handleSaveFlow = () => {
        setShowModal(true);
    };

    const handleModalSubmit = () => {
        if (!flowName.trim())
            return notify("error", "Please enter a flow name.");
        const data = {
            nodes,
            edges,
        };

        const urlParams = new URLSearchParams(window.location.search);
        const accountId = urlParams.get("account");

        let URL = `${BASE_URL}/user/flow-builder/store?account_id=${accountId ? accountId : 0}`;
        if (isEditing && flowId)
            URL = `${BASE_URL}/user/flow-builder/update/${flowId}?account_id=${accountId ? accountId : 0}`;
        axios
            .post(URL, {
                data: JSON.stringify(data),
                name: flowName,
            })
            .then((response) => {
                if (response.data.status == "error")
                    return notify("error", response.data.message);

                notify("success", response.data.message);
                setShowModal(false);
                setNodes(initialNodes);
                setEdges([]);
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            })
            .catch((error) => {
                notify("error", error.message);
            });


    };

    return (
        <div style={{ display: "flex", height: "85vh", width: "100%" }}>
            <div style={{ flexGrow: 1, position: "relative" }}>
                <ReactFlow
                    nodes={nodes}
                    edges={edges}
                    onNodesChange={onNodesChange}
                    onEdgesChange={onEdgesChange}
                    onConnect={onConnect}
                    nodeTypes={nodeTypes}
                    onDragOver={onDragOver}
                    onDrop={onDrop}
                    fitView
                >
                    <MiniMap />
                    <Controls />
                    <Background />
                </ReactFlow>

                <div
                    className={`flow_top_button_wrapper ${isSidebarOpen ? "sidebar_open" : ""
                        }`}
                >
                    <button
                        onClick={handleSaveFlow}
                        className="top_btn"
                        data-bs-toggle="tooltip"
                        data-bs-placement="left"
                        data-bs-title={isEditing ? "Update Flow" : "Save Flow"}
                    >
                        <i className="las la-save"></i>
                    </button>

                    <button
                        onClick={() => setIsSidebarOpen(true)}
                        className="top_btn"
                        data-bs-toggle="tooltip"
                        data-bs-placement="left"
                        data-bs-title="Add node as a step"
                    >
                        <i className="las la-plus"></i>
                    </button>
                </div>
            </div>
            <Sidebar
                isOpen={isSidebarOpen}
                onClose={() => setIsSidebarOpen(false)}
            />
            <div className={`flow_modal ${showModal ? "show" : ""}`}>
                <div>
                    <h5>Enter Flow Name</h5>
                    <div className="form-group">
                        <input
                            type="text"
                            value={flowName}
                            onChange={(e) => setFlowName(e.target.value)}
                            placeholder="Flow Name"
                            autoFocus
                            className="form-control form--control"
                        />
                    </div>
                    <div className="d-flex gap-2 justify-content-end">
                        <button
                            onClick={() => setShowModal(false)}
                            className="btn btn--dark"
                        >
                            <i className="las la-times me-2"></i>
                            Cancel
                        </button>
                        <button
                            onClick={handleModalSubmit}
                            className="btn btn--base"
                        >
                            <i className="lab la-telegram me-2"></i>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}

if (document.getElementById("flow-builder")) {
    ReactDOM.createRoot(document.getElementById("flow-builder")).render(
        <FlowBuilder />
    );
}