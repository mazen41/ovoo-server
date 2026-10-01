import React, { useState, useCallback } from "react";

export const Position = {
    Left: "left",
    Right: "right",
    Top: "top",
    Bottom: "bottom",
};

export const Handle = ({ type, position, id }) =>
    React.createElement("span", {
        "data-handle-type": type,
        "data-handle-position": position,
        "data-handle-id": id || "",
    });

export const ReactFlow = ({ nodes = [], edges = [] }) =>
    React.createElement("div", {
        "data-node-count": String(nodes.length),
        "data-trigger-count": String(
            nodes.filter((n) => n.type === "triggerNode").length
        ),
        "data-node-ids": nodes.map((n) => n.id).join(","),
        "data-edge-count": String(edges.length),
    });

export const MiniMap = () => null;
export const Controls = () => null;
export const Background = () => null;

export function useNodesState(initial) {
    const [nodes, setNodes] = useState(initial);
    const onNodesChange = useCallback(() => {}, []);
    return [nodes, setNodes, onNodesChange];
}

export function useEdgesState(initial) {
    const [edges, setEdges] = useState(initial);
    const onEdgesChange = useCallback(() => {}, []);
    return [edges, setEdges, onEdgesChange];
}

export const addEdge = (params, eds) => [...eds, params];

export default ReactFlow;
