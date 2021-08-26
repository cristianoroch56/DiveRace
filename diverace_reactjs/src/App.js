import React from "react";
import Login from "./components/Login/index";
import Register from "./components/Register/index";
import ForgotPassword from "./components/ForgotPassword/index";
import ConfirmPayment from "./components/StepWizard/ConfirmPayment";
import EditAddOns from "./components/StepWizard/EditAddOns";
import OrderSummary from "./components/Summary/OrderSummary";
import BookedTrip from "./components/Summary/BookedTrip";
import OrderFail from "./components/Summary/OrderFail";
import { HashRouter as Router , Switch, Route } from "react-router-dom";
import StepWizard from "./components/StepWizard/index";
import EditAddOnsNew from "./components/UpdateTrip/index"

import SelectTrip3 from './components/StepWizard/SelectTrip3'
import SelectCabinsNew from './components/StepWizard/SelectCabinsNew'
import SelectAddOnsNew from './components/StepWizard/SelectAddOnsNew'
import "./App.css";


function App() {
  return (
      <Router>
        <Switch>

       
          <Route exact strict path="/login" component={Login} />
          <Route exact strict path="/register" component={Register} />
          <Route exact strict path="/forgot_password" component={ForgotPassword} />
          <Route exact strict path="/confirm_payment" component={ConfirmPayment} />
          <Route exact strict path="/order_summary" component={OrderSummary} />
          <Route exact strict path="/order_details" component={BookedTrip} />
          {/* <Route exact strict path="/update_order/:order_id" component={EditAddOns} /> */}
          <Route exact strict path="/update_order/:order_id" component={EditAddOnsNew} />
          <Route exact strict path="/order_fail" component={OrderFail} />
          <Route path="/:vessel?/:cabin?/:cabinType?" component={StepWizard} />

        </Switch>
      </Router>
  );
}

export default App;
