import imgLoader from './img/loader.svg'
import {APP_POST_MESSAGE_ACCEPT} from "../client/app/slice/app/app.constants";
import {APP_AUTH} from "../client/app/slice/app/app.actions";
import {USER_AUTH} from "../client/app/slice/user/user.actions";

(function () {
  const appUrl = 'http://localhost:8081/app/'
  // <script async="" data-type="stromcom" data-dl="comLayer" data-ck="" _src="http://localhost:8080/snippet.js"></script>

  let customerConfig = {
    // ClientKey
    ck: '',
    // ClientSecret
    cs: '',
    // Key of dataLayer in window
    dl: 'comLayer',
    // type (stromcom)
    type : 'stromcom'
  }
  const scripts= document.getElementsByTagName("script");
  for (let i = 0; i < scripts.length; i++) {
    const script = scripts[i];

    if(script.dataset['type'] === customerConfig.type) {
      customerConfig = Object.assign(customerConfig, script.dataset);
    }
  }

  if(!customerConfig.ck) {
    console.warn("[stromcom] No customer configuration found! clientKey is required");
    return;
  }

  if(!customerConfig.cs) {
    console.warn("[stromcom] No customer configuration found! clientSecret is required");
    return;
  }

  let pageConfig = window[customerConfig.dl] ||  {
    user : {
      code: null,
      name: null,
      emailAddress: null
    }
  }

  if(!pageConfig['user'] || !pageConfig['user']['code'] || !pageConfig['user']['name']) {
    console.warn(`[stromcom] 'user' is required in window['${customerConfig.dl}']. Please define current user {code: string, name: string, emailAddress: ?string}`);
    return;
  }

  function createSendMessage(iframe) {
    const targetOrigin = (new URL(iframe.getAttribute('src'))).origin;

    return function(type, payload) {
      iframe.contentWindow.postMessage(
        {
          source: APP_POST_MESSAGE_ACCEPT.SOURCE,
          type: type,
          payload : payload
        },
        targetOrigin
      )
    }
  }


  /**
   *
   * @param {HTMLElement} node
   */
  function injectApp(node, {code, name, url}) {
    if(!String(code)) {
      console.warn("[stromcom] 'code' is required");
      return false;
    }

    const query = new URLSearchParams()
    query.append("thread[code]", code)
    if(name) {
      query.append("thread[name]", name)
    }
    if(url) {
      query.append("thread[url]", url)
    }

    const iframe = document.createElement('iframe')
    iframe.src = appUrl+customerConfig.ck+'/thread/concept/?'+query.toString()
    iframe.dataset.loading = '1';

    const iframeStyle = {
      backgroundImage: `url('data:image/svg+xml;charset=UTF-8,${encodeURIComponent(String(imgLoader))}')`,
      backgroundPosition: 'center',
      backgroundColor: "rgb(61 166 255 / 7%)",
      backgroundSize: "clamp(50px, 25%, 80px)",
      backgroundRepeat: "no-repeat",
      width: "100%",
      height: "100%",
      border: "none",
    };

    Object.keys(iframeStyle).forEach(key => {
      iframe.style[key] = iframeStyle[key];
    })


    const sendMessage = createSendMessage(iframe);

    iframe.addEventListener('load', () => {
      iframe.dataset.loading = '0';
      iframe.style.backgroundImage = '';

      sendMessage(APP_AUTH, {
        secret : customerConfig.cs
      });

      sendMessage(USER_AUTH, pageConfig.user);
    })


    node.innerHTML = '';
    node.appendChild(iframe)
  }

  document.querySelectorAll('*[data-sc-code]').forEach(el => {
    const code = el.dataset['scCode'] || null;
    const name = el.dataset['scName'] || code;
    const url = el.dataset['scUrl'] || null;

    injectApp(el, {code, name, url})
  })


})();