import { renderToStaticMarkup } from "react-dom/server.browser";

// Stands in for ReactDOM.createRoot so app.jsx can be exercised without a DOM.
// The element is kept so the harness can render it again after changing what the
// page hands the builder: FlowBuilder reads the dataset on every render, and any
// state that leaks into module scope shows up as a difference between renders.
export function createRoot() {
    return {
        render(element) {
            globalThis.__renderApp = () => renderToStaticMarkup(element);
            globalThis.__appRenders = [
                globalThis.__renderApp(),
                globalThis.__renderApp(),
            ];
        },
    };
}

export default { createRoot };
