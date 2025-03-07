import Entity from "./Entity.js";
export default class CustomerEntity extends Entity {

  _properties = {
    name: '',
    isActive: false,
    createdAt: null,
  }


  get name() {
    return this._properties.name;
  }

  get isActive() {
    return this._properties.isActive;
  }

  get createdAt() {
    return this._properties.createdAt;
  }


  constructor(props) {
    super();
    this._initProperties(props);
  }

}

