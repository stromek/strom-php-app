import {createRoot} from "react-dom/client";
import React from "react";
import App from "./app/app.jsx";


(function() {
  let rootElement = document.getElementById('root');

  if(rootElement) {
    rootElement.remove()
  }

  rootElement = document.createElement('div');
  rootElement.id = 'root';
  document.body.append(rootElement);

  // Render your React component instead
  const root = createRoot(rootElement);
  root.render(<App />);
})();



if (module.hot) {
  console.groupEnd();
  console.group("Reload");
  module.hot.accept();
}