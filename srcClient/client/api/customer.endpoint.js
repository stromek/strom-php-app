import ApiEndpoint from "./apiEndpoint.js";
import CustomerEntity from "../entity/CustomerEntity";
import UserEntity from "../entity/UserEntity";

export default class CustomerEndpoint extends ApiEndpoint {

  /**
   * @returns {CustomerEntity|null}
   */
  async info() {
    const {data} = await this.fetch().get('customer/');

    return new CustomerEntity(data);
  }


  async users() {
    const {data} = await this.fetch().get('customer/users/');

    return data.map((data) => {
      return new UserEntity(data);
    })
  }

}