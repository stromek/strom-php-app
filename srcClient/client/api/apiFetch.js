class ApiFetch {

  /**
   * @typedef {keyof ApiFetch['#EVENT']} EventType
   */

  /**
   * @var {String}
   */
  #baseUrl;

  /**
   * #var {String}
   */
  #authToken;


  /**
   * @type {Object.<EventType, Function[]>}
   * @private
   */
  #listeners = {
  }

  /**
   * @readonly
   * @type {Object}
   */
  #EVENT = {
    REQUEST: 'request',
    REQUEST_ERROR : 'error',
    REQUEST_APP_ERROR : 'appError',
  }

  /**
   * @readonly
   * @type {{SUCCESS: string, ERROR: string}}
   */
  STATUS = {
    SUCCESS : "success",
    ERROR : "error"
  }

  constructor({baseUrl, authToken}) {
    this.#baseUrl = baseUrl;
    this.setAuthToken(authToken);
  }


  /**
   * @param {String} token
   */
  setAuthToken(token) {
    this.#authToken = token;
  }


  /**
   * @param {EventType} EVENT_TYPE
   * @param {Function} callback
   */
  listen(EVENT_TYPE, callback) {
    this.#isEventValid(EVENT_TYPE);

    if (typeof callback !== 'function') {
      throw new Error('Callback must be a function');
    }

    this.#listeners[EVENT_TYPE] = this.#listeners[EVENT_TYPE] || [];
    this.#listeners[EVENT_TYPE].push(callback);
  }

  /**
   * @param {EventType} EVENT_TYPE
   * @param {*} payload
   * @param payload
   */
  #emit(EVENT_TYPE, payload) {
    this.#isEventValid(EVENT_TYPE);

    const list = this.#listeners[EVENT_TYPE] || [];
    list.forEach((cb) => {
      cb(payload);
    })
  }


  /**
   * @param {String} url
   * @param {Object} path
   * @param {Object} params
   * @return Promise
   */
  getPath(url, path = {}, params) {
    Object.keys(path).forEach((key) => {
      let value = path[key].replace(/\//g, '_');
      url = url.replace(`[${key}]`, value);
    })

    return this.get(url, params);
  }


  /**
   * @param {String} path
   * @param {Object} params
   * @return Promise
   */
  get(path, params = {}) {
    this.#emit(this.#EVENT.REQUEST, {path, params});

    const url = this.#createUrl(path, params);
    const headers = this.#createHeaders()


    return fetch(url, {
      method: 'GET',
      headers: headers,
    })
      .then((response) => {
        if(!response.ok) {
          this.#emit(this.#EVENT.REQUEST_ERROR, response);
        }
        return response.json();
      })
      .then((data) => {
        this.#validateResponse(data);

        if(data.status == this.STATUS.SUCCESS) {
          return {data : data.data, meta: data.meta};
        }else {
          throw new Error(`[${data.error.code}] ${data.error.message}`);
        }
      })
  }


  #validateResponse(data) {
    if(!('status' in data)) {
      throw new Error("[invalid response]. Missing 'status'");
    }

    if(Object.values(this.STATUS).indexOf(data.status) === -1) {
      throw new Error("[invalid response]. Invalid value '"+JSON.stringify(data.status)+"' as 'status'");
    }

    if(!('data' in data)) {
      throw new Error("[invalid response]. Missing 'data'");
    }

    if(!('meta' in data)) {
      throw new Error("[invalid response]. Missing 'meta'");
    }

    if('error' in data) {
      if(!('code' in data['error'])) {
        throw new Error("[invalid response]. Missing 'error.code'");
      }

      if(!('message' in data['error'])) {
        throw new Error("[invalid response]. Missing 'error.message'");
      }

      if(!('details' in data['error'])) {
        throw new Error("[invalid response]. Missing 'error.details'");
      }
    }
  }


  #createCorrelation(length = 10) {
    let s = '';
    for (let i = 0; i < 5; i++) {
      s += (Number(Math.round(Math.random() * Math.pow(10, 5))).toString(36))
      if(s.length > length) {
        return s;
      }
    }

    return s;
  }


  #createHeaders(headers = []) {
    return {...headers,
      Authorization: `Bearer ${this.#authToken}`,
      Correlation_id: this.#createCorrelation(10)
    };
  }

  /**
   * @param {String} url
   * @param {Object }params
   * @returns {string}
   */
  #createUrl(url, params = {}) {
    let result = '';

    if(this.#baseUrl.slice(-1) === '/' && url.slice(0, 1) === '/') {
      result = this.#baseUrl + url.slice(1)
    }else {
      result = this.#baseUrl + url;
    }

    const queryString = this.#createQueryString(params);
    if(queryString) {
      result += '?'+queryString;
    }

    return result;
  }


  /**
   * @param {Object} params
   * @returns {string}
   */
  #createQueryString(params = {}) {
    return new URLSearchParams(params).toString();
  }


  /**
   * @param {EventType }EVENT_TYPE
   */
  #isEventValid(EVENT_TYPE) {
    if(Object.values(this.#EVENT).indexOf(EVENT_TYPE) === -1) {
      throw new Error(`Invalid event type: ${EVENT_TYPE}`);
    }
  }
}



export default ApiFetch