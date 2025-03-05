import { connect } from 'react-redux'
import LayoutComponent from './layout.component.jsx'
import {alert} from "../../slice/app/app.actions";

function mapStateToProps(state, props) {
  console.log({state, props});
  return {
    messages : [],
  }
}

function mapDispatchToProps(dispatch, props) {
  return {
    alert : function(val) {
      dispatch(alert(val));
    },
    // loadProviders : function() {
    //   dispatch(loadProviders());
    // },
    // syncTime : function() {
    //   dispatch(syncTime());
    // },
    // appListen : function() {
    //   dispatch(appListen());
    // }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(LayoutComponent)