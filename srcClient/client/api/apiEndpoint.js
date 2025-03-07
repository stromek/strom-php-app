export default class ApiEndpoint {

  /**
   * @var {ApiClient}
   */
  #apiClient;

  /**
   * @var {ApiFetch}
   */
  #apiFetch;

  /**
   * @param {ApiClient} apiClient
   * @param {ApiFetch} apiFetch
   */
  constructor(apiClient, apiFetch) {
    this.#apiClient = apiClient;
    this.#apiFetch = apiFetch;
  }

  /**
   *
   * @returns {ApiFetch}
   */
  fetch() {
    return this.#apiFetch
  }

}