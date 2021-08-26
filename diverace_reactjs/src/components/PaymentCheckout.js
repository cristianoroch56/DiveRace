import React, { useEffect } from "react";
import StripeCheckout from "react-stripe-checkout";
import { useDispatch, useSelector } from "react-redux";
import { useHistory} from "react-router-dom";
import {orderUpdatedByUser,updateForm} from "../store/actions/StepWizardAction";

const PaymentCheckout = () => {
  
  const redux_store = useSelector((state) => state.StepWizardReducer);
  const updated_final_amount = useSelector((state) => state.StepWizardReducer.updated_final_amount);
  const userLoginData = useSelector((state) => state.user.profile);

  const dispatch = useDispatch();
  let history = useHistory();

  const onToken = (token, addresses) => {

    
    if (Object.entries(token).length !== 0) {

      dispatch(updateForm('showLoader',true));

      let transaction_id = "transaction_id";
      const card_id = "card_id";
      const client_ip = "client_ip";
      const created = "created";
      const email = "email";
      
      var transactionDetails = {
        [transaction_id]: token.id,
        [card_id]: token.card.id,
        [client_ip]: token.client_ip,
        [created]: token.created,
        [email]: token.email,
      };

      let filterCourses = redux_store.order_booked_courses;
      var filtered_courses = filterCourses.filter(function(el) { return el.booked_course != 0; });
      let final_courses = filtered_courses.map((obj_course)=>{
        return {
            'id':obj_course.id,
            'booked_course':obj_course.booked_course,
        }
      })


      let filterRentals = redux_store.order_booked_equipments;
      var filtered_rentals = filterRentals.filter(function(el) { return el.booked_rental_equipment != 0; });
      let final_rentals=  filtered_rentals.map((obj_rental)=>{
        return {
            'id':obj_rental.id,
            'booked_rental_equipment':obj_rental.booked_rental_equipment,
        }
      })

      let requestData = {

        user_id: Number(userLoginData.ID) ,
        order_id: Number(redux_store.order_booked_summary.id) ,
        user_credit:redux_store.user_credit,
        courses: final_courses,
        rental_equipment:final_rentals,
        final_payble_amount:updated_final_amount,
        transaction_data : transactionDetails
      }
      
      setTimeout(() => {
        dispatch(orderUpdatedByUser(requestData ,history));
      }, 1000);

    }
  };

  // payment without stripe
  const paymentAmount = () => {
    if (Number(updated_final_amount) == 0) {

      dispatch(updateForm('showLoader',true));

      let transaction_id = "transaction_id";
      const card_id = "card_id";
      const client_ip = "client_ip";
      const created = "created";
      const email = "email";
      
      const transactionDetails = {
        [transaction_id]: 'NA',
        [card_id]: 'NA',
        [client_ip]: 'NA',
        [created]: 'NA',
        [email]: 'NA',
      };

      let filterCourses = redux_store.order_booked_courses;
      var filtered_courses = filterCourses.filter(function(el) { return el.booked_course != 0; });
      let final_courses = filtered_courses.map((obj_course)=>{
        return {
            'id':obj_course.id,
            'booked_course':obj_course.booked_course,
        }
      })


      let filterRentals = redux_store.order_booked_equipments;
      var filtered_rentals = filterRentals.filter(function(el) { return el.booked_rental_equipment != 0; });
      let final_rentals=  filtered_rentals.map((obj_rental)=>{
        return {
            'id':obj_rental.id,
            'booked_rental_equipment':obj_rental.booked_rental_equipment,
        }
      })

      let requestData = {

        user_id: Number(userLoginData.ID) ,
        order_id: Number(redux_store.order_booked_summary.id) ,
        user_credit:redux_store.user_credit,
        courses: final_courses,
        rental_equipment:final_rentals,
        final_payble_amount:redux_store.final_pay_amount,
        transaction_data : transactionDetails
      }

      setTimeout(() => {
        dispatch(orderUpdatedByUser(requestData ,history));
      }, 1000);

      
    }
  }

  useEffect(() => {}, []);

  return (

    <React.Fragment>
      {Number(updated_final_amount) === 0 ?
          <div>
            
          <button className="btn btn-primary btn-lg" onClick={paymentAmount} disabled={redux_store.final_pay_amount > 0.00 ? false : true}>Proceed To Payment</button>
        </div> : 
          <StripeCheckout
          stripeKey="pk_test_XuxReVpfIJThZQmYTXVwCcXV"
          amount={Number(updated_final_amount * 100)}
          currency="SGD"
          token={onToken}
        >
          {/* Custom Button */}
          <button className="btn btn-primary btn-lg" disabled={updated_final_amount > 0.00 ? false : true}>Proceed To Payment</button>
          {/* Custom Button */}
        </StripeCheckout>
      }
    </React.Fragment>
    
  );
};

export default PaymentCheckout;
