import { createRoot } from 'react-dom/client';

import './../css/style.css'


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
  root.render(
    <div>
      Hello world!
    </div>
  );
})();
