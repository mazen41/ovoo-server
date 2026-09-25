import { useState, useEffect } from "react";
import { Position } from "reactflow";
import NodeWrapper from "./NodeWrapper";
import axios from "axios";

export default function SendCtaUrl({ id, data, setNodes }) {
    const handles = data.handles || [
        { type: "target", position: Position.Left },
    ];
    const BASE_URL = document
        .querySelector("meta[name=APP-DOMAIN]")
        .getAttribute("content");

    const [ctaUrls, setCtaUrls] = useState([]);
    const [selectedId, setSelectedId] = useState(data.selectedCta?.id || "");

    useEffect(() => {
        axios
            .get(`${BASE_URL}/user/cta-url/get-list`)
            .then((res) => {
                const fetched = res.data.data.ctaUrls || [];
                setCtaUrls(fetched);

                if (fetched.length == 0) return;

                if (selectedId) return;

                const first = fetched[0];
                setSelectedId(first.id.toString());

                setNodes((nds) =>
                    nds.map((node) =>
                        node.id == id
                            ? {
                                  ...node,
                                  data: { ...node.data, selectedCta: first },
                              }
                            : node
                    )
                );
            })
            .catch((err) => {
                console.error("Failed to fetch CTA URLs:", err);
            });
    }, []);

    const handleSelect = (e) => {
        const ctaId = e.target.value;
        setSelectedId(ctaId);

        const selectedCta = ctaUrls.find((cta) => cta.id.toString() === ctaId);

        setNodes((nds) =>
            nds.map((node) =>
                node.id === id
                    ? { ...node, data: { ...node.data, selectedCta } }
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
                    <i className="las la-link"></i> CTA URL
                </h6>
            }
            content={
                <div>
                    {ctaUrls.length > 0 ? (
                        <select
                            className="form-select form--control"
                            value={selectedId}
                            onChange={handleSelect}
                        >
                            {ctaUrls.map((cta) => (
                                <option key={cta.id} value={cta.id}>
                                    {cta.name}
                                </option>
                            ))}
                        </select>
                    ) : (
                        <span className="no-data">No data found</span>
                    )}
                </div>
            }
            handles={handles}
        />
    );
}
