import ApiEndpoint from "./apiEndpoint.js";
import ThreadEntity from "../entity/ThreadEntity.js";

export default class ThreadEndpoint extends ApiEndpoint {

  /**
   * @params {string} threadHash
   * @returns {CustomerEntity|null}
   */
  async getByHash(threadHash) {
    const {data} = await this.fetch().getPath('thread/[hash]/', {hash: threadHash});

    return new ThreadEntity(data);
  }

  async getByCode(threadCode) {
    const {data} = await this.fetch().get('thread/find/', {code: threadCode});

    return new ThreadEntity(data);
  }


  // async messages(threadHash) {
  //   const {data} = await this.fetch().get('customer/users/');
  //
  //   return data.map((data) => {
  //     return new UserEntity(data);
  //   })
  // }

}