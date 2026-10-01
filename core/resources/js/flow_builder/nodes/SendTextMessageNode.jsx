import NodeWrapper from "./NodeWrapper.jsx";

export default function SendTextMessageNode({ id, data, setNodes }) {
    const handles = data.handles || [
        { type: "target", position: Position.Left },
    ];

    return (
        <NodeWrapper
            id={id}
            setNodes={setNodes}
            title={<h6 className="mb-0"> <i className="las la-envelope"></i> Text Message</h6>}
            content={
                <textarea
                    value={data.message || ""}
                    onChange={(e) =>
                        setNodes((nds) =>
                            nds.map((node) =>
                                node.id === id
                                    ? {
                                          ...node,
                                          data: {
                                              ...node.data,
                                              message: e.target.value,
                                          },
                                      }
                                    : node
                            )
                        )
                    }
                    className="form-control form--control w-full"
                    cols={20}
                    rows={3}
                    placeholder="Type your message here..."
                />
            }
            handles={handles}
        />
    );
}
