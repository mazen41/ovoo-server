export default function Sidebar({ isOpen, onClose }) {
    const sidebarClass = isOpen ? "show" : "";

    const nodeOptions = [
        {
            type: "textMessage",
            icon: "las la-envelope",
            label: "Text Message",
            description: "Send a simple text message to the user.",
        },
        {
            type: "sendImage",
            icon: "las la-image",
            label: "Send Image",
            description: "Send an image message to the user.",
        },
        {
            type: "sendVideo",
            icon: "las la-video",
            label: "Video Message",
            description: "Send a video file as a message.",
        },
        {
            type: "sendDocument",
            icon: "las la-file-alt",
            label: "Document Message",
            description: "Send a document or file to the user.",
        },
        {
            type: "sendAudio",
            icon: "las la-microphone",
            label: "Audio Message",
            description: "Send an audio clip or voice message.",
        },
        {
            type: "sendList",
            icon: "las la-list",
            label: "List Message",
            description: "Send a structured list message with options.",
        },
        {
            type: "sendCtaUrl",
            icon: "las la-link",
            label: "URL Message",
            description: "Send a call-to-action message containing a URL.",
        },
        {
            type: "sendTemplate",
            icon: "las la-envelope-open-text",
            label: "Message Template",
            description: "Send a message template to the user.",
        },
        {
            type: "sendLocation",
            icon: "las la-map-marker-alt",
            label: "Location Message",
            description: "Share a location pin with the user.",
        },
        {
            type: "sendButton",
            icon: "las la-stream",
            label: "Button Message",
            description: "Make conditional choices with a interactive buttons.",
        },
    ];

    return (
        <div className={`flow_sidebar ${sidebarClass}`}>
            <button onClick={onClose} className="flow_sidebar_close_btn">
                <i className="las la-times"></i>
            </button>
            <div style={{ padding: "16px" }}>
                <div className="pb-2 border-bottom">
                    <h5 className="m-0 p-0">Add Node</h5>
                    <span className="text-muted fs-14">
                        Drag and drop to add node
                    </span>
                </div>
                {nodeOptions.map((node) => (
                    <div
                        className="flow_sidebar_button"
                        key={node.type}
                        draggable
                        onDragStart={(event) => {
                            event.dataTransfer.setData(
                                "application/reactFlow",
                                node.type
                            );
                            event.dataTransfer.effectAllowed = "move";
                        }}
                    >
                        <div className="icon">
                            <i className={node.icon}></i>
                        </div>
                        <div className="content">
                            <span className="content-title">{node.label}</span>
                            <p className="description">{node.description}</p>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}