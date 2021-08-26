import React from 'react';
import Header from "../Header";
import Footer from "../Footer";
import PaymentCheckout from "../PaymentCheckout";
import OrderBookedSummary from "../Summary/OrderBookedSummary";
import { Link } from "react-router-dom";
import {
  fetchBookedRentalEquipment,
  fetchBookedCourses, updateForm
} from "../../store/actions/StepWizardAction";
import { useDispatch, useSelector } from "react-redux";

const UpdateAddOns = (props) => {
  const { match: { params } } = props;

  const courses_list = useSelector((state) => state.StepWizardReducer.order_booked_courses);
  const rental_equipment_list = useSelector((state) => state.StepWizardReducer.order_booked_equipments);


  return (
    <React.Fragment>
      {/* Header */}
      <Header></Header>
      {/* Header */}

      <Footer></Footer>
      {/* Footer */}
    </React.Fragment>
  );
};

export default UpdateAddOns;