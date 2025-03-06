
/**
 * Zadání autorizačních přístupů
 */
export const APP_AUTH = "APP/AUTH";


export function appAuthorize(clientSecret) {
  return async(dispatch) => {
    dispatch({
      type : APP_AUTH,
      payload : {clientSecret}
    })
  }
}
