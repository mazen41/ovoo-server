import { useState, useEffect } from "react";
import { Position } from "reactflow";
import NodeWrapper from "./NodeWrapper";
import axios from "axios";

export default function SendTemplateNode({ id, data, setNodes }) {
    const handles = data.handles || [
        { type: "target", position: Position.Left },
    ];
    const BASE_URL = document
        .querySelector("meta[name=APP-DOMAIN]")
        .getAttribute("content");

    const [templates, setTemplates] = useState([]);
    const [selectedId, setSelectedId] = useState(
        data.selectedTemplate?.id || ""
    );

    const [headerVars, setHeaderVars] = useState(data.headerVars || {});
    const [bodyVars, setBodyVars] = useState(data.bodyVars || {});

    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const accountId = urlParams.get("account");
        axios
            .get(`${BASE_URL}/user/template/get-list?account_id=${accountId ? accountId : 0}`)
            .then((res) => {
                const fetched = res.data.data.templates || [];
                setTemplates(fetched);

                if (fetched.length == 0) return;

                if (selectedId) return;

                const first = fetched[0];
                setSelectedId(first.id.toString());

                setNodes((nds) =>
                    nds.map((node) =>
                        node.id == id
                            ? {
                                ...node,
                                data: {
                                    ...node.data,
                                    selectedTemplate: first,
                                },
                            }
                            : node
                    )
                );
            })
            .catch((err) => {
                console.error("Failed to fetch templates:", err);
            });
    }, []);

    function extractVariables(text) {
        if (!text) return [];
        const matches = text.match(/\{\{\d+\}\}/g);
        return matches ? [...new Set(matches)] : [];
    }

    const handleSelect = (e) => {
        const templateId = e.target.value;
        setSelectedId(templateId);

        const selectedTemplate = templates.find(
            (t) => t.id.toString() == templateId
        );

        const headerText = selectedTemplate?.header?.text || "";
        const bodyText = selectedTemplate?.body || "";

        const foundHeaderVars = extractVariables(headerText);
        const foundBodyVars = extractVariables(bodyText);

        const initialHeaderVars = {};
        foundHeaderVars.forEach((v) => (initialHeaderVars[v] = ""));

        const initialBodyVars = {};
        foundBodyVars.forEach((v) => (initialBodyVars[v] = ""));

        setHeaderVars(initialHeaderVars);
        setBodyVars(initialBodyVars);

        // Save to node
        setNodes((nds) =>
            nds.map((node) =>
                node.id === id
                    ? {
                        ...node,
                        data: {
                            ...node.data,
                            selectedTemplate,
                            headerVars: initialHeaderVars,
                            bodyVars: initialBodyVars,
                        },
                    }
                    : node
            )
        );
    };

    const updateHeaderVar = (key, value) => {
        setHeaderVars((prev) => {
            const updated = { ...prev, [key]: value };

            setNodes((nds) =>
                nds.map((node) =>
                    node.id === id
                        ? {
                            ...node,
                            data: { ...node.data, headerVars: updated },
                        }
                        : node
                )
            );

            return updated;
        });
    };

    const updateBodyVar = (key, value) => {
        setBodyVars((prev) => {
            const updated = { ...prev, [key]: value };

            setNodes((nds) =>
                nds.map((node) =>
                    node.id === id
                        ? { ...node, data: { ...node.data, bodyVars: updated } }
                        : node
                )
            );

            return updated;
        });
    };

    return (
        <NodeWrapper
            id={id}
            setNodes={setNodes}
            title={
                <h6 className="mb-0">
                    <i className="las la-link"></i> Send Template
                </h6>
            }
            content={
                <div>
                    {templates.length > 0 ? (
                        <>
                            <select
                                className="form-select form--control mb-2"
                                value={selectedId}
                                onChange={handleSelect}
                            >
                                {templates.map((template) => (
                                    <option
                                        key={template.id}
                                        value={template.id}
                                    >
                                        {template.name}
                                    </option>
                                ))}
                            </select>

                            {Object.keys(headerVars).length > 0 && (
                                <div className="mb-2">
                                    <h6 className="m-0 variable-title">
                                        Header Variables
                                    </h6>
                                    {Object.keys(headerVars).map((key) => (
                                        <input
                                            key={key}
                                            className="form-control form--control my-1"
                                            placeholder={`Value for ${key}`}
                                            value={headerVars[key]}
                                            onChange={(e) =>
                                                updateHeaderVar(
                                                    key,
                                                    e.target.value
                                                )
                                            }
                                        />
                                    ))}
                                </div>
                            )}

                            {Object.keys(bodyVars).length > 0 && (
                                <div>
                                    <h6 className="m-0 variable-title">
                                        Body Variables
                                    </h6>
                                    {Object.keys(bodyVars).map((key) => (
                                        <input
                                            key={key}
                                            className="form-control form--control my-1"
                                            placeholder={`Value for ${key}`}
                                            value={bodyVars[key]}
                                            onChange={(e) =>
                                                updateBodyVar(
                                                    key,
                                                    e.target.value
                                                )
                                            }
                                        />
                                    ))}
                                </div>
                            )}
                        </>
                    ) : (
                        <span className="no-data">No data found</span>
                    )}
                </div>
            }
            handles={handles}
        />
    );
}