import { useState } from "react";
import NodeWrapper from "./NodeWrapper.jsx";
import { Position } from "reactflow";

export default function SendLocationNode({ id, data, setNodes }) {
    const handles = data.handles || [
        { type: "target", position: Position.Left },
        { type: "source", position: Position.Right },
    ];

    const [locationData, setLocationData] = useState({
        latitude: data.latitude || "",
        longitude: data.longitude || "",
    });

    const handleChange = (field, value) => {
        setLocationData((prev) => ({ ...prev, [field]: value }));
        setNodes((nds) =>
            nds.map((node) =>
                node.id === id
                    ? { ...node, data: { ...node.data, [field]: value } }
                    : node
            )
        );
    };

    return (
        <NodeWrapper
            id={id}
            setNodes={setNodes}
            title={<h6 className="mb-0"> <i className="las la-map-marker-alt"></i> Send Location</h6>}
            content={
                <div className="text-sm text-gray-700 space-y-2">
                    <input
                        type="text"
                        placeholder="Latitude"
                        className="form-control form--control mb-2"
                        value={locationData.latitude}
                        onChange={(e) =>
                            handleChange("latitude", e.target.value)
                        }
                    />
                    <input
                        type="text"
                        placeholder="Longitude"
                        className="form-control form--control"
                        value={locationData.longitude}
                        onChange={(e) =>
                            handleChange("longitude", e.target.value)
                        }
                    />
                </div>
            }
            handles={handles}
        />
    );
}
