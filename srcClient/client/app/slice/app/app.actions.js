export const APP_ALERT = "APP/ALERT";


export function alert(alertMsg = null) {
  return async(dispatch) => {

    dispatch({
      type : APP_ALERT,
      payload : alertMsg
    })
  }
}

