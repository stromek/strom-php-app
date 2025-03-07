export default class Entity {

  _properties = {}


  /**
   * @param {Object} properties
   */
  _initProperties(properties = {}) {
    Object.keys(properties).forEach((name) => {
      this._setProperty(name, properties[name]);
    })
  }


  /**
   * @param {string} name
   * @param {any} value
   * @protected
   */
  _setProperty(name, value) {
    if(!(name in this._properties)) {
      throw new Error(`Cannot set property '${name}'. ${this.constructor.name}::_properties[${name}]' not exists!`);
    }

    this._properties[name] = this.#formatPropertyValue(value);
  }


  #formatPropertyValue(value) {
    if(value !== null && typeof(value) === 'object' && 'date' in value && 'timezone' in value && 'timezone_type' in value) {
      if(value.timezone !== 'UTC') {
        throw new Error("Cannot format property 'timezone' in value, only UTC is allowed.");
      }

      return new Date(value.date.replace(' ', 'T')+'Z');
    }

    return value;
  }

}