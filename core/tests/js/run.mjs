// Headless render check for the two flow-builder React fixes.
//
// Nothing here touches a browser: reactflow, axios/http and react-dom/client are
// replaced with stubs, and the components are rendered with react-dom/server.browser.
// Run it with "current" or "baseline" to compare the fixed sources against the
// versions committed in HEAD.

import { fileURLToPath } from "node:url";
import path from "node:path";
import fs from "node:fs";
import { execFileSync } from "node:child_process";
import esbuild from "esbuild";

const here = path.dirname(fileURLToPath(import.meta.url));
const core = path.resolve(here, "../..").split(path.sep).join("/");
const flowDir = `${core}/resources/js/flow_builder`;

const variant = process.argv[2] === "baseline" ? "baseline" : "current";

// "baseline" renders the versions committed in git HEAD, so the same checks can be shown
// failing before the fix and passing after it. The copies keep their original directory so
// their relative imports still resolve, and they are deleted again on the way out.
const baselineCopies = [
    { from: "core/resources/js/flow_builder/app.jsx", to: `${flowDir}/app.baseline.jsx` },
    {
        from: "core/resources/js/flow_builder/nodes/SendListMessageNode.jsx",
        to: `${flowDir}/nodes/SendListMessageNode.baseline.jsx`,
    },
];

if (variant === "baseline") {
    for (const copy of baselineCopies) {
        fs.writeFileSync(
            copy.to,
            execFileSync("git", ["show", `HEAD:${copy.from}`], {
                cwd: core,
                encoding: "utf8",
            })
        );
    }
    process.on("exit", () => {
        for (const copy of baselineCopies) fs.rmSync(copy.to, { force: true });
    });
}
const appPath =
    variant === "baseline" ? `${flowDir}/app.baseline.jsx` : `${flowDir}/app.jsx`;
const listPath =
    variant === "baseline"
        ? `${flowDir}/nodes/SendListMessageNode.baseline.jsx`
        : `${flowDir}/nodes/SendListMessageNode.jsx`;

const stubs = {
    reactflow: `${here}/stub-reactflow.js`,
    http: `${here}/stub-http.js`,
    reactDomClient: `${here}/stub-reactdom-client.js`,
};

const stubPlugin = {
    name: "stubs",
    setup(build) {
        build.onResolve({ filter: /^reactflow(\/.*)?$/ }, () => ({
            path: stubs.reactflow,
        }));
        build.onResolve({ filter: /(^|\/)http(\.js)?$/ }, () => ({
            path: stubs.http,
        }));
        build.onResolve({ filter: /^axios$/ }, () => ({ path: stubs.http }));
        build.onResolve({ filter: /^react-dom\/client$/ }, () => ({
            path: stubs.reactDomClient,
        }));
    },
};

const entry = `
import React from "react";
import { renderToStaticMarkup } from "react-dom/server.browser";
import SendListMessageNode from ${JSON.stringify(listPath)};

const noop = () => {};

globalThis.__listRenders = {
    // A node dropped fresh on the canvas, before anything is persisted.
    fresh: renderToStaticMarkup(
        React.createElement(SendListMessageNode, { id: "n1", data: {}, setNodes: noop })
    ),
    // A node rehydrated from a saved flow: app.jsx's onDrop persists both handles.
    saved: renderToStaticMarkup(
        React.createElement(SendListMessageNode, {
            id: "n1",
            setNodes: noop,
            data: {
                handles: [
                    { type: "target", position: "left" },
                    { type: "source", position: "right" },
                ],
            },
        })
    ),
};

import ${JSON.stringify(appPath)};
`;

// FlowBuilderController stores json_encode($nodes) for the whole canvas, so a saved flow
// carries its own trigger node. The fixture has to match that or the duplication checks
// below would be testing a payload the app never produces.
const savedTrigger = {
    id: "1",
    type: "triggerNode",
    position: { x: -700, y: 110 },
    data: {
        nodeId: "1",
        trigger: "keyword_match",
        keyword: "hello",
        handles: [{ type: "source", position: "right" }],
    },
};
const savedSteps = [
    { id: "a1", type: "textMessage", position: { x: 0, y: 0 }, data: { nodeId: "a1" } },
    { id: "a2", type: "sendList", position: { x: 200, y: 0 }, data: { nodeId: "a2" } },
];
const savedNodes = [savedTrigger, ...savedSteps];
const savedEdges = [{ id: "e1", source: "1", target: "a1" }];

const flowBuilderElement = {
    dataset: {
        trigger: "keyword_match",
        keyword: "hello",
        nodes: JSON.stringify(savedNodes),
        edges: JSON.stringify(savedEdges),
        name: "Saved flow",
        id: "7",
    },
};

globalThis.window = { innerHeight: 900, location: { search: "" } };
globalThis.document = {
    getElementById: (id) => (id === "flow-builder" ? flowBuilderElement : null),
    querySelector: () => null,
};

const bundlePath = `${here}/bundle.${variant}.mjs`;

await esbuild.build({
    stdin: { contents: entry, resolveDir: flowDir, loader: "jsx", sourcefile: "harness.jsx" },
    bundle: true,
    format: "esm",
    platform: "node",
    outfile: bundlePath,
    plugins: [stubPlugin],
    nodePaths: [`${core}/node_modules`],
    loader: { ".css": "empty" },
    jsx: "automatic",
    logLevel: "error",
    banner: {
        js: "import { createRequire } from \"module\"; const require = createRequire(import.meta.url);",
    },
});

await import(`file:///${bundlePath.replace(/\\/g, "/")}`);

const attr = (html, name) => {
    const m = html.match(new RegExp(`${name}="([^"]*)"`));
    return m ? m[1] : null;
};
const countHandles = (html, type) =>
    (html.match(new RegExp(`data-handle-type="${type}"`, "g")) || []).length;

const renders = globalThis.__appRenders || [];
const list = globalThis.__listRenders;

// A flow saved before the trigger node was persisted with the rest of the canvas: the
// builder still has to put a trigger on screen, or the flow cannot be re-saved.
flowBuilderElement.dataset.nodes = JSON.stringify(savedSteps);
const legacyRender = globalThis.__renderApp();

const checks = [
    {
        name: "list node offers a source handle so it can be wired to a next step",
        got: countHandles(list.fresh, "source"),
        want: 1,
    },
    {
        name: "list node keeps its target handle",
        got: countHandles(list.fresh, "target"),
        want: 1,
    },
    {
        name: "rehydrated list node renders the handles saved with the flow",
        got: countHandles(list.saved, "source"),
        want: 1,
    },
    {
        name: "editing a saved flow shows exactly the nodes it was saved with",
        got: attr(renders[0], "data-node-ids"),
        want: savedNodes.map((n) => n.id).join(","),
    },
    {
        name: "exactly one trigger node when editing a saved flow",
        got: Number(attr(renders[0], "data-trigger-count")),
        want: 1,
    },
    {
        name: "re-mounting the builder does not grow the canvas",
        got: Number(attr(renders[1], "data-node-count")),
        want: savedNodes.length,
    },
    {
        name: "re-mounting the builder does not duplicate nodes",
        got: attr(renders[1], "data-node-ids"),
        want: savedNodes.map((n) => n.id).join(","),
    },
    {
        name: "a flow saved without a trigger node still gets one",
        got: Number(attr(legacyRender, "data-trigger-count")),
        want: 1,
    },
    {
        name: "a flow saved without a trigger node keeps its steps",
        got: attr(legacyRender, "data-node-ids"),
        want: `1,${savedSteps.map((n) => n.id).join(",")}`,
    },
];

let failed = 0;
console.log(`\n=== flow builder React checks (${variant}) ===`);
for (const c of checks) {
    const ok = String(c.got) === String(c.want);
    if (!ok) failed++;
    console.log(
        `${ok ? "PASS" : "FAIL"}  ${c.name}\n        expected ${JSON.stringify(
            c.want
        )}, got ${JSON.stringify(c.got)}`
    );
}
console.log(
    `\n${checks.length - failed}/${checks.length} passed, ${failed} failed\n`
);
process.exit(failed ? 1 : 0);
