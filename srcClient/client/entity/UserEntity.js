import Entity from "./Entity.js";

export default class UserEntity extends Entity {

  _properties = {
    name: '',
    code: '',
    hash: '',
    emailAddress: null,
    avatarURL: null,
    createdAt: null,
  }


  get name() {
    return this._properties.name;
  }

  get code() {
    return this._properties.code;
  }

  get hash() {
    return this._properties.hash;
  }

  get emailAddress() {
    return this._properties.emailAddress;
  }

  get avatarURL() {
    return this._properties.avatarURL;
  }

  get createdAt() {
    return this._properties.createdAt;
  }


  constructor(props) {
    super();
    this._initProperties(props);
  }

}

