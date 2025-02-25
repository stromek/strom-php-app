import { connect } from 'react-redux'
import LayoutComponent from './layout.component.jsx'
import {alert} from "../../slice/app/app.actions";

function mapStateToProps(state, props) {
  return {}
}

function mapDispatchToProps(dispatch, props) {
  return {
    alert : function(val) {
      dispatch(alert(val));
    }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(LayoutComponent)