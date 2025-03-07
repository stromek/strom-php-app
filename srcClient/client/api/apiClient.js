import ApiFetch from "./apiFetch.js";
import CustomerEndpoint from "./customer.endpoint.js";
import ThreadEndpoint from "./thread.endpoint.js";

class ApiClient {


  /**
   * @var {ApiFetch}
   */
  #api;

  #endpoints = {}

  constructor(baseUrl, authToken) {
    this.#api = new ApiFetch({baseUrl, authToken});
  }

  customer() {
    if(this.#endpoints['customer']) {
      return this.#endpoints['customer'];
    }

    return this.#endpoints['customer'] = new CustomerEndpoint(this, this.#api);
  }


  thread () {
    if(this.#endpoints['thread']) {
      return this.#endpoints['thread'];
    }

    return this.#endpoints['thread'] = new ThreadEndpoint(this, this.#api);
  }

}


export function createApiClient(baseUrl, authToken) {
  return new ApiClient(baseUrl, authToken);
}