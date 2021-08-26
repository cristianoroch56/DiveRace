import { combineReducers } from "redux";
import StepWizardReducer from "./StepWizardReducer";
import user from "./userReducer";
import SummaryReducer from "./SummaryReducer";

const index = combineReducers({user ,StepWizardReducer,SummaryReducer});

export default index;