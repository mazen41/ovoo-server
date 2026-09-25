import { useState, useEffect } from "react";
import NodeWrapper from "./NodeWrapper.jsx";
import { Position } from "reactflow";
import axios from "axios";

export default function SendListMessageNode({ id, data, setNodes }) {
    const handles = [{ type: "target", position: Position.Left }];
    const BASE_URL = document
        .querySelector("meta[name=APP-DOMAIN]")
        .getAttribute("content");

    const [lists, setLists] = useState([]);
    const [selectedId, setSelectedId] = useState(data.selectedList?.id || "");

    useEffect(() => {
        axios
            .get(`${BASE_URL}/user/interactive-list/get-list`)
            .then((res) => {
                const fetchedLists = res.data.data.lists || [];
                setLists(fetchedLists);

                if (fetchedLists.length === 0) return;

                if (selectedId) return;

                const first = fetchedLists[0];
                setSelectedId(first.id.toString());

                setNodes((nds) =>
                    nds.map((node) =>
                        node.id === id
                            ? {
                                  ...node,
                                  data: { ...node.data, selectedList: first },
                              }
                            : node
                    )
                );
            })
            .catch((err) => console.error("Failed to fetch lists:", err));
    }, []);

    const handleSelect = (e) => {
        const listId = e.target.value;
        setSelectedId(listId);

        const selectedList = lists.find((l) => l.id.toString() === listId);

        setNodes((nds) =>
            nds.map((node) =>
                node.id === id
                    ? { ...node, data: { ...node.data, selectedList } }
                    : node
            )
        );
    };

    return (
        <NodeWrapper
            id={id}
            setNodes={setNodes}
            title={
                <h6 className="mb-0">
                    <i className="las la-list"></i> Interactive List
                </h6>
            }
            handles={handles}
            content={
                <div className="list-node">
                    {lists.length > 0 ? (
                        <select
                            className="form-select form--control mb-3"
                            value={selectedId}
                            onChange={handleSelect}
                        >
                            {lists.map((list) => (
                                <option key={list.id} value={list.id}>
                                    {list.name || `List #${list.id}`}
                                </option>
                            ))}
                        </select>
                    ) : (
                        <span className="no-data">No data found</span>
                    )}
                </div>
            }
        />
    );
}
